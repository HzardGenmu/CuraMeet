import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { authValidator } from './authValidator';

describe('authValidator', () => {
  // Store original localStorage
  const originalLocalStorage = global.localStorage;

  beforeEach(() => {
    // Clear localStorage before each test
    localStorage.clear();
  });

  afterEach(() => {
    // Clean up after each test
    localStorage.clear();
  });

  describe('getAuthData', () => {
    it('should return null values when no auth data exists', () => {
      const authData = authValidator.getAuthData();
      expect(authData.authToken).toBeNull();
      expect(authData.userInfo).toBeNull();
      expect(authData.rememberMe).toBeNull();
    });

    it('should return stored auth data', () => {
      localStorage.setItem('authToken', 'test-token');
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      
      const authData = authValidator.getAuthData();
      expect(authData.authToken).toBe('test-token');
      expect(authData.userInfo).toBeTruthy();
    });
  });

  describe('hasValidToken', () => {
    it('should return false when no token exists', () => {
      expect(authValidator.hasValidToken()).toBe(false);
    });

    it('should return true when token exists', () => {
      localStorage.setItem('authToken', 'test-token');
      expect(authValidator.hasValidToken()).toBe(true);
    });

    it('should return false for empty token', () => {
      localStorage.setItem('authToken', '');
      expect(authValidator.hasValidToken()).toBe(false);
    });
  });

  describe('hasValidUserInfo', () => {
    it('should return false when no userInfo exists', () => {
      expect(authValidator.hasValidUserInfo()).toBe(false);
    });

    it('should return true when valid userInfo exists', () => {
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      expect(authValidator.hasValidUserInfo()).toBe(true);
    });

    it('should return false for invalid JSON', () => {
      localStorage.setItem('userInfo', 'invalid-json');
      expect(authValidator.hasValidUserInfo()).toBe(false);
    });
  });

  describe('isAuthComplete', () => {
    it('should return false when no auth data exists', () => {
      expect(authValidator.isAuthComplete()).toBe(false);
    });

    it('should return false when only token exists', () => {
      localStorage.setItem('authToken', 'test-token');
      expect(authValidator.isAuthComplete()).toBe(false);
    });

    it('should return false when only userInfo exists', () => {
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      expect(authValidator.isAuthComplete()).toBe(false);
    });

    it('should return true when both token and userInfo exist', () => {
      localStorage.setItem('authToken', 'test-token');
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      expect(authValidator.isAuthComplete()).toBe(true);
    });
  });

  describe('findInconsistencies', () => {
    it('should return empty array when no auth data exists', () => {
      const inconsistencies = authValidator.findInconsistencies();
      expect(inconsistencies).toHaveLength(0);
    });

    it('should detect token without userInfo', () => {
      localStorage.setItem('authToken', 'test-token');
      const inconsistencies = authValidator.findInconsistencies();
      expect(inconsistencies).toHaveLength(1);
      expect(inconsistencies[0]).toContain('userInfo is missing');
    });

    it('should detect userInfo without token', () => {
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      const inconsistencies = authValidator.findInconsistencies();
      expect(inconsistencies).toHaveLength(1);
      expect(inconsistencies[0]).toContain('token is missing');
    });

    it('should return empty array when auth is complete', () => {
      localStorage.setItem('authToken', 'test-token');
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'patient' }));
      const inconsistencies = authValidator.findInconsistencies();
      expect(inconsistencies).toHaveLength(0);
    });
  });

  describe('getAuthStatus', () => {
    it('should return complete status object', () => {
      localStorage.setItem('authToken', 'test-token-12345');
      localStorage.setItem('userInfo', JSON.stringify({ 
        id: 1, 
        role: 'patient',
        email: 'test@example.com'
      }));

      const status = authValidator.getAuthStatus();
      
      expect(status.isAuthenticated).toBe(true);
      expect(status.hasToken).toBe(true);
      expect(status.hasUserInfo).toBe(true);
      expect(status.tokenLength).toBe(16);
      expect(status.userRole).toBe('patient');
      expect(status.userId).toBe(1);
      expect(status.userEmail).toBe('test@example.com');
      expect(status.inconsistencies).toHaveLength(0);
    });
  });

  describe('clearAuthData', () => {
    it('should clear all auth data', () => {
      localStorage.setItem('authToken', 'test-token');
      localStorage.setItem('userInfo', JSON.stringify({ id: 1 }));
      localStorage.setItem('rememberMe', 'true');

      authValidator.clearAuthData();

      expect(localStorage.getItem('authToken')).toBeNull();
      expect(localStorage.getItem('userInfo')).toBeNull();
      expect(localStorage.getItem('rememberMe')).toBeNull();
    });
  });

  describe('validateTokenFormat', () => {
    it('should return false for null token', () => {
      expect(authValidator.validateTokenFormat(null)).toBe(false);
    });

    it('should return false for undefined token', () => {
      expect(authValidator.validateTokenFormat(undefined)).toBe(false);
    });

    it('should return false for non-string token', () => {
      expect(authValidator.validateTokenFormat(123)).toBe(false);
    });

    it('should return false for short token', () => {
      expect(authValidator.validateTokenFormat('short')).toBe(false);
    });

    it('should return true for valid length token', () => {
      expect(authValidator.validateTokenFormat('valid-token-string-here')).toBe(true);
    });
  });

  describe('getParsedUserInfo', () => {
    it('should return null when no userInfo exists', () => {
      expect(authValidator.getParsedUserInfo()).toBeNull();
    });

    it('should return parsed object for valid JSON', () => {
      const userInfo = { id: 1, role: 'doctor', email: 'doctor@example.com' };
      localStorage.setItem('userInfo', JSON.stringify(userInfo));
      
      const parsed = authValidator.getParsedUserInfo();
      expect(parsed).toEqual(userInfo);
    });

    it('should return null for invalid JSON', () => {
      localStorage.setItem('userInfo', '{invalid-json}');
      expect(authValidator.getParsedUserInfo()).toBeNull();
    });
  });

  describe('getDebugInfo', () => {
    it('should return debug information object', () => {
      localStorage.setItem('authToken', 'test-token-12345');
      localStorage.setItem('userInfo', JSON.stringify({ id: 1, role: 'admin' }));

      const debugInfo = authValidator.getDebugInfo();

      expect(debugInfo).toHaveProperty('timestamp');
      expect(debugInfo).toHaveProperty('authToken');
      expect(debugInfo).toHaveProperty('userInfo');
      expect(debugInfo).toHaveProperty('localStorage');
      expect(debugInfo.localStorage).toHaveProperty('itemCount');
      expect(debugInfo.localStorage).toHaveProperty('keys');
    });

    it('should truncate token in debug info', () => {
      localStorage.setItem('authToken', 'very-long-token-string-that-should-be-truncated');

      const debugInfo = authValidator.getDebugInfo();

      expect(debugInfo.authToken).toBe('very-long-...');
    });
  });
});
