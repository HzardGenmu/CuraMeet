# Authentication Debug Tools

Tools for testing and debugging the authentication system in CuraMeet frontend.

## Features

### 1. 🧪 Auth Testing Component
Component for testing login/logout flows with different user roles.

**Features:**
- Test login flow with configurable credentials
- Test logout flow
- Test token storage validation
- Test user info consistency
- Test session persistence
- Test role-based data
- Run all tests at once
- View detailed test results with timestamps

**Usage:**
Navigate to `/auth-debug` and click on the "🧪 Testing" tab.

### 2. 🔍 Debug Panel
Real-time monitoring panel for localStorage, session, and token status.

**Features:**
- Auto-refresh with configurable intervals (0.5s to 5s)
- Visual status indicators for authentication state
- Display auth token and user info
- Detect and highlight inconsistencies in auth data
- Display user role with color-coded badges
- Show localStorage debug information
- Clear auth data button with confirmation

**Usage:**
Navigate to `/auth-debug` and click on the "🔍 Debug Panel" tab.

### 3. 🌐 Network Inspector
Monitor API calls with authentication headers.

**Features:**
- Capture all axios API requests
- Display request method, URL, and status
- Show authorization headers
- Filter requests (all, with auth, without auth, success, failed)
- View detailed request/response information
- Track token presence in requests
- Request summary statistics
- Expandable request details

**Usage:**
Navigate to `/auth-debug` and click on the "🌐 Network" tab.

### 4. Auth Validator Utility
Utility for validating consistency of auth data.

**Location:** `/src/utils/authValidator.js`

**Key Functions:**
- `getAuthStatus()` - Get detailed authentication status
- `isAuthComplete()` - Check if auth is complete and consistent
- `findInconsistencies()` - Detect auth data inconsistencies
- `clearAuthData()` - Clear all auth data from localStorage
- `getDebugInfo()` - Get debug information for logging

## Access

The auth debug tools can be accessed at:
```
http://localhost:3000/auth-debug
```

## Components Location

```
frontend/src/
├── components/
│   └── AuthDebugTools/
│       ├── AuthDebugTools.jsx       # Main component with tabs
│       ├── AuthDebugTools.css
│       ├── AuthTestingComponent.jsx # Testing component
│       ├── AuthTestingComponent.css
│       ├── DebugPanel.jsx          # Debug panel component
│       ├── DebugPanel.css
│       ├── NetworkInspector.jsx    # Network monitoring component
│       ├── NetworkInspector.css
│       └── index.js
└── utils/
    └── authValidator.js            # Auth validation utility
```

## Testing Different User Roles

The testing component supports three user roles:
- **Patient** - Default role for regular users
- **Doctor** - Medical professionals
- **Admin** - System administrators

Each role can be tested with custom credentials in the Testing tab.

## Visual Indicators

### Status Indicators
- ✓ (Green) - Valid/Success
- ✗ (Red) - Invalid/Error
- ⚠ (Yellow) - Warning
- ℹ (Blue) - Info
- ⟳ (Blue, spinning) - Running/In Progress

### Role Badges
- **ADMIN** - Red badge
- **DOCTOR** - Blue badge
- **PATIENT** - Green badge

## Debug Workflow

1. **Check Current State**
   - Open Debug Panel to see current auth status
   - Check for any inconsistencies

2. **Test Login Flow**
   - Go to Testing tab
   - Configure test credentials
   - Click "Test Login Flow"
   - Check results for any issues

3. **Monitor Network Requests**
   - Go to Network tab
   - Perform actions in the app
   - Check if auth headers are included in requests
   - Filter by "With Auth Header" to see authenticated requests

4. **Test Logout Flow**
   - In Testing tab, click "Test Logout Flow"
   - Verify token is cleared in Debug Panel

5. **Clear Auth State**
   - Use "Clear Auth" button in Debug Panel
   - Useful for testing fresh login scenarios

## Troubleshooting

### Token Not Stored
- Check Debug Panel for inconsistencies
- Verify token is returned from API
- Check browser console for errors

### Session Not Persisting
- Run "Test Session Persistence" in Testing tab
- Check if userInfo is stored in localStorage
- Verify token format is valid

### API Requests Without Auth Header
- Use Network Inspector to identify requests
- Check if token exists in localStorage
- Verify axios interceptor is working

### Inconsistent Auth Data
- Debug Panel will highlight inconsistencies
- Common issue: token exists but userInfo missing (or vice versa)
- Use "Clear Auth" and re-login to resolve

## Development Notes

- All components use localStorage for auth data
- Network Inspector uses axios interceptors
- Components auto-refresh to show real-time data
- Designed with dark theme matching VS Code
- Mobile-responsive design included

## Security Considerations

⚠️ **Important:** These tools are for development/debugging only. 
- Do not expose in production builds
- Token previews are truncated for security
- Sensitive data is not fully displayed
- Consider removing `/auth-debug` route in production

## Example Use Cases

### Scenario 1: Debug Login Not Working
1. Open Auth Debug Tools at `/auth-debug`
2. Go to Testing tab
3. Enter credentials and click "Test Login Flow"
4. Check test results for specific errors
5. Switch to Debug Panel to see auth state
6. Check Network tab for API response

### Scenario 2: Token Expiration Testing
1. Login normally through the app
2. Open Debug Panel
3. Monitor token status
4. Manually clear token using "Clear Auth"
5. Try to make API calls
6. Check Network Inspector for 401 errors

### Scenario 3: Role-Based Access Testing
1. Use Testing tab to test different roles
2. Login as Patient, Doctor, and Admin
3. Check Debug Panel for correct role assignment
4. Verify role-specific data in userInfo

## Future Enhancements

Potential improvements:
- Token expiration countdown timer
- Export logs functionality
- API call replay feature
- Mock authentication responses
- Performance metrics
- WebSocket connection monitoring
