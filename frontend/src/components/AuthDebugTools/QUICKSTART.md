# Authentication Debug Tools - Quick Start Guide

## Overview
The Authentication Debug Tools provide a comprehensive suite for testing and debugging the CuraMeet authentication system. Access the tools at `http://localhost:3000/auth-debug`.

## Components

### 1. 🧪 Authentication Testing
Test various authentication scenarios without manual login/logout.

**Key Features:**
- Configure test credentials (email, password, role)
- Test individual auth components:
  - Login Flow - Tests complete login process
  - Logout Flow - Tests logout and token cleanup
  - Token Storage - Validates token in localStorage
  - User Info - Checks user data consistency
  - Session Persistence - Verifies data persistence
  - Role Data - Validates role assignments
- Run All Tests - Execute all tests simultaneously
- Color-coded results (Success, Error, Warning, Info, Running)
- Detailed test result history with expandable details

### 2. 🔍 Debug Panel
Real-time monitoring of authentication state.

**Key Features:**
- Auto-refresh with configurable intervals (0.5s - 5s)
- Auth Status indicators:
  - Authenticated (Yes/No)
  - Has Token (Yes/No)
  - Has User Info (Yes/No)
  - Token Length
- User Information display:
  - User ID
  - Email
  - Role (with color-coded badge)
- Inconsistency detection and alerts
- LocalStorage data viewer (raw and parsed)
- Debug information with timestamps
- Clear Auth button for resetting state

### 3. 🌐 Network Inspector
Monitor all API requests with authentication headers.

**Key Features:**
- Real-time request capture using axios interceptors
- Request summary statistics (Total, With Auth, Success, Failed)
- Filters:
  - All Requests
  - With Auth Header
  - Without Auth Header
  - Successful
  - Failed
- Detailed request information:
  - Method (GET, POST, PUT, DELETE, PATCH)
  - URL
  - Status code
  - Authorization header
  - Response data
  - Timestamps
- Expandable details for each request
- Configurable history size (25, 50, 100 requests)

## Common Use Cases

### Debugging Login Issues
1. Navigate to `/auth-debug`
2. Go to **Testing** tab
3. Enter test credentials
4. Click **Test Login Flow**
5. Check results for errors
6. Switch to **Debug Panel** to verify token storage
7. Check **Network** tab for API call details

### Verifying Token Persistence
1. Login to the application normally
2. Open `/auth-debug`
3. Go to **Debug Panel**
4. Verify:
   - Authenticated: Yes ✓
   - Has Token: Yes ✓
   - Has User Info: Yes ✓
   - No inconsistencies detected

### Monitoring API Authentication
1. Open `/auth-debug`
2. Go to **Network** tab
3. Perform actions in the app (in another tab)
4. Return to Network Inspector
5. Filter by "With Auth Header"
6. Verify all protected endpoints have auth headers

### Testing Different Roles
1. Go to **Testing** tab
2. Select role: Patient/Doctor/Admin
3. Click **Test Role Data**
4. Verify correct role assignment
5. Check **Debug Panel** for role badge

### Clearing Stuck Sessions
1. Go to **Debug Panel**
2. Review current auth state
3. Click **Clear Auth** button
4. Confirm the action
5. Verify all auth data is cleared

## Visual Indicators

### Status Icons
- ✓ Green circle - Valid/Success
- ✗ Red circle - Invalid/Error
- ⚠ Yellow triangle - Warning
- ℹ Blue info - Information
- ⟳ Blue spinner - Running/In Progress

### Role Badges
- **ADMIN** - Red badge
- **DOCTOR** - Blue badge  
- **PATIENT** - Green badge

### Request Methods
- **GET** - Green badge
- **POST** - Blue badge
- **PUT** - Yellow badge
- **DELETE** - Red badge
- **PATCH** - Purple badge

## Keyboard Shortcuts
- Tab through controls with `Tab` key
- Activate buttons with `Enter` or `Space`
- Expand/collapse details with `Enter` on summary

## Best Practices

1. **Always check Debug Panel first** when debugging auth issues
2. **Use Network Inspector** to verify API calls include auth headers
3. **Run All Tests** periodically to ensure auth system health
4. **Clear Auth** when testing fresh login scenarios
5. **Monitor inconsistencies** in Debug Panel for data integrity

## Security Notes

⚠️ **Important:**
- These tools are for development/debugging only
- Do not expose `/auth-debug` route in production
- Token previews are truncated for security
- Sensitive data is not fully displayed
- Remove or protect this route in production builds

## Troubleshooting

### Issue: No requests showing in Network Inspector
**Solution:** The inspector captures requests made after it's loaded. Refresh the page or make new requests.

### Issue: Tests failing with network errors
**Solution:** Ensure the backend API is running and accessible.

### Issue: Auto-refresh not working
**Solution:** Check that "Auto-refresh" checkbox is enabled and select an interval.

### Issue: Can't see test details
**Solution:** Click the "View Details" dropdown on test results to expand.

## Technical Details

- Built with React 19
- Styling with custom CSS (VS Code dark theme inspired)
- Uses axios interceptors for network monitoring
- localStorage for auth data management
- Real-time updates with React state management
- Fully responsive design

## Support

For issues or questions:
1. Check the comprehensive README at `frontend/src/components/AuthDebugTools/README.md`
2. Review test cases in `frontend/src/utils/authValidator.test.js`
3. Check browser console for error messages
4. Review Network tab in browser DevTools

---

**Version:** 1.0.0  
**Last Updated:** 2025-10-12  
**Author:** CuraMeet Development Team
