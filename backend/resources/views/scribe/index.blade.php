<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-admin" class="tocify-header">
                <li class="tocify-item level-1" data-unique="admin">
                    <a href="#admin">Admin</a>
                </li>
                                    <ul id="tocify-subheader-admin" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-roles-manage">
                                <a href="#admin-POSTapi-admin-roles-manage">Manage User Roles</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-logs-activity">
                                <a href="#admin-GETapi-admin-logs-activity">Monitor Activity Logs</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-users-bulk-manage">
                                <a href="#admin-POSTapi-admin-users-bulk-manage">Bulk User Role Management</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-logs-audit">
                                <a href="#admin-GETapi-admin-logs-audit">Audit Log Data Management</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-logs-api-requests">
                                <a href="#admin-GETapi-admin-logs-api-requests">API Request Logging</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-monitoring-backend">
                                <a href="#admin-GETapi-admin-monitoring-backend">Backend System Monitoring</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-monitoring-traffic-anomaly">
                                <a href="#admin-GETapi-admin-monitoring-traffic-anomaly">Traffic Anomaly Detection</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-system-maintenance">
                                <a href="#admin-POSTapi-admin-system-maintenance">System Maintenance Operations</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-users-impersonate">
                                <a href="#admin-POSTapi-admin-users-impersonate">Impersonate User</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-database-backup">
                                <a href="#admin-POSTapi-admin-database-backup">Backup Database</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-config-manage">
                                <a href="#admin-POSTapi-admin-config-manage">Manage Configuration</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-artisan-execute">
                                <a href="#admin-POSTapi-admin-artisan-execute">Execute Artisan Command</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-appointments" class="tocify-header">
                <li class="tocify-item level-1" data-unique="appointments">
                    <a href="#appointments">Appointments</a>
                </li>
                                    <ul id="tocify-subheader-appointments" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-new">
                                <a href="#appointments-POSTapi-appointments-new">Create New Appointment</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments--appointmentId--cancel">
                                <a href="#appointments-POSTapi-appointments--appointmentId--cancel">Cancel Appointment (Patient)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-cancel-by-doctor">
                                <a href="#appointments-POSTapi-appointments-cancel-by-doctor">Cancel Appointment by Doctor ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-GETapi-appointments-doctor">
                                <a href="#appointments-GETapi-appointments-doctor">Get Appointments by Doctor</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-GETapi-appointments-patient">
                                <a href="#appointments-GETapi-appointments-patient">Get Appointments by Patient</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-change-schedule-doctor">
                                <a href="#appointments-POSTapi-appointments-change-schedule-doctor">Change Schedule by Doctor</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-cancel-doctor">
                                <a href="#appointments-POSTapi-appointments-cancel-doctor">Cancel Appointment by Doctor</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-change-schedule-patient">
                                <a href="#appointments-POSTapi-appointments-change-schedule-patient">Change Appointment by Patient</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="appointments-POSTapi-appointments-bulk-update">
                                <a href="#appointments-POSTapi-appointments-bulk-update">Bulk Update Appointments</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-login">
                                <a href="#authentication-POSTapi-auth-login">User Login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-register">
                                <a href="#authentication-POSTapi-auth-register">User Registration</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-password-reset">
                                <a href="#authentication-POSTapi-auth-password-reset">Reset Password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-email-check">
                                <a href="#authentication-POSTapi-auth-email-check">Check Email Availability</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-GETapi-auth-token-verify">
                                <a href="#authentication-GETapi-auth-token-verify">Verify Token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-GETapi-auth-user">
                                <a href="#authentication-GETapi-auth-user">Get Current Authenticated User</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-logout">
                                <a href="#authentication-POSTapi-auth-logout">User Logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-token-refresh">
                                <a href="#authentication-POSTapi-auth-token-refresh">Refresh Token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-password-change">
                                <a href="#authentication-POSTapi-auth-password-change">Change Password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-auth-profile-update">
                                <a href="#authentication-POSTapi-auth-profile-update">Update User Profile</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-doctors" class="tocify-header">
                <li class="tocify-item level-1" data-unique="doctors">
                    <a href="#doctors">Doctors</a>
                </li>
                                    <ul id="tocify-subheader-doctors" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="doctors-GETapi-doctors">
                                <a href="#doctors-GETapi-doctors">List All Doctors</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-GETapi-doctors-search">
                                <a href="#doctors-GETapi-doctors-search">Search Doctors by Name</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-GETapi-doctors--doctorId-">
                                <a href="#doctors-GETapi-doctors--doctorId-">Get Doctor by ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-GETapi-doctors-user--userId-">
                                <a href="#doctors-GETapi-doctors-user--userId-">Get Doctor by User ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-POSTapi-doctors-medical-records-view">
                                <a href="#doctors-POSTapi-doctors-medical-records-view">View Medical Records (Doctor)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-POSTapi-doctors-patients--patientId--export">
                                <a href="#doctors-POSTapi-doctors-patients--patientId--export">Export Patient Data</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-POSTapi-doctors-schedule-update">
                                <a href="#doctors-POSTapi-doctors-schedule-update">Update Doctor Schedule</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="doctors-GETapi-doctors-profile-now">
                                <a href="#doctors-GETapi-doctors-profile-now">Get Current Doctor Profile</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-OPTIONSapi--any-">
                                <a href="#endpoints-OPTIONSapi--any-">OPTIONS api/{any}</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-medical-records" class="tocify-header">
                <li class="tocify-item level-1" data-unique="medical-records">
                    <a href="#medical-records">Medical Records</a>
                </li>
                                    <ul id="tocify-subheader-medical-records" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="medical-records-GETapi-medical-records-patient">
                                <a href="#medical-records-GETapi-medical-records-patient">Get Medical Records by Patient ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="medical-records-GETapi-medical-records--id-">
                                <a href="#medical-records-GETapi-medical-records--id-">Get Medical Record by ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="medical-records-POSTapi-medical-records-update">
                                <a href="#medical-records-POSTapi-medical-records-update">Update Medical Record</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="medical-records-DELETEapi-medical-records--id--delete">
                                <a href="#medical-records-DELETEapi-medical-records--id--delete">Delete Medical Record by ID</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-patients" class="tocify-header">
                <li class="tocify-item level-1" data-unique="patients">
                    <a href="#patients">Patients</a>
                </li>
                                    <ul id="tocify-subheader-patients" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="patients-GETapi-patients-search">
                                <a href="#patients-GETapi-patients-search">Search Patients by Name</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="patients-GETapi-patients--patientId-">
                                <a href="#patients-GETapi-patients--patientId-">Get Patient by ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="patients-GETapi-patients-user--userId-">
                                <a href="#patients-GETapi-patients-user--userId-">Get Patient by User ID</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="patients-POSTapi-patients--patientId--profile-fill">
                                <a href="#patients-POSTapi-patients--patientId--profile-fill">Update Patient Personal Data</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="patients-GETapi-patients--patientId--statistics">
                                <a href="#patients-GETapi-patients--patientId--statistics">View Patient Statistics</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="patients-GETapi-patients-profile-now">
                                <a href="#patients-GETapi-patients-profile-now">Get Current Patient Profile</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 16, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="admin">Admin</h1>

    

                                <h2 id="admin-POSTapi-admin-roles-manage">Manage User Roles</h2>

<p>
</p>

<p>VULNERABILITY 57: No admin verification - anyone can change any user's role.
VULNERABILITY 58: Logs admin operations with sensitive data.
VULNERABILITY 23: Privilege escalation and SQL injection in role management.
VULNERABILITY 24: Sensitive operation logging exposes password hashes.</p>
<p>Allows changing any user's role including elevation to admin.
No authorization check or audit trail.</p>

<span id="example-requests-POSTapi-admin-roles-manage">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/roles/manage" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"user_id\": 1,
    \"new_role\": \"admin\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/roles/manage"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "user_id": 1,
    "new_role": "admin"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-roles-manage">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;user_info&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;user@example.com&quot;,
        &quot;password&quot;: &quot;$2y$10$hashedpassword...&quot;,
        &quot;role&quot;: &quot;admin&quot;
    },
    &quot;new_role&quot;: &quot;admin&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-roles-manage" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-roles-manage"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-roles-manage"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-roles-manage" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-roles-manage">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-roles-manage" data-method="POST"
      data-path="api/admin/roles/manage"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-roles-manage', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-roles-manage"
                    onclick="tryItOut('POSTapi-admin-roles-manage');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-roles-manage"
                    onclick="cancelTryOut('POSTapi-admin-roles-manage');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-roles-manage"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/roles/manage</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-roles-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-roles-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="POSTapi-admin-roles-manage"
               value="1"
               data-component="body">
    <br>
<p>The ID of the user to change role (vulnerable to SQL injection). Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_role"                data-endpoint="POSTapi-admin-roles-manage"
               value="admin"
               data-component="body">
    <br>
<p>New role to assign (patient, doctor, admin). Example: <code>admin</code></p>
        </div>
        </form>

                    <h2 id="admin-GETapi-admin-logs-activity">Monitor Activity Logs</h2>

<p>
</p>

<p>VULNERABILITY 59: Unrestricted activity monitoring - anyone can view all logs.
VULNERABILITY 60: Additional system information exposure (server info, env vars, phpinfo).
VULNERABILITY 25: Information disclosure in activity monitoring.
VULNERABILITY 26: Exposes sensitive user data (passwords, tokens) in logs.</p>
<p>Returns activity logs with sensitive user information.
Exposes complete system environment and server details.</p>

<span id="example-requests-GETapi-admin-logs-activity">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/logs/activity?user_id=1&amp;action=login&amp;date_from=2024-01-01" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/logs/activity"
);

const params = {
    "user_id": "1",
    "action": "login",
    "date_from": "2024-01-01",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-logs-activity">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;activity_logs&quot;: {
        &quot;success&quot;: true,
        &quot;logs&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;user_id&quot;: 1,
                &quot;action&quot;: &quot;login&quot;,
                &quot;created_at&quot;: &quot;2024-01-15 10:00:00&quot;,
                &quot;email&quot;: &quot;user@example.com&quot;,
                &quot;password&quot;: &quot;$2y$10$hashedpassword...&quot;,
                &quot;remember_token&quot;: &quot;abc123def456&quot;
            }
        ],
        &quot;query_executed&quot;: &quot;SELECT al.*, u.email, u.password...&quot;,
        &quot;total_logs&quot;: 1
    },
    &quot;system_info&quot;: {
        &quot;current_user&quot;: {},
        &quot;server_info&quot;: {},
        &quot;environment_vars&quot;: {},
        &quot;php_info&quot;: &quot;phpinfo output&quot;
    },
    &quot;request_details&quot;: {}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-logs-activity" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-logs-activity"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-logs-activity"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-logs-activity" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-logs-activity">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-logs-activity" data-method="GET"
      data-path="api/admin/logs/activity"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-logs-activity', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-logs-activity"
                    onclick="tryItOut('GETapi-admin-logs-activity');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-logs-activity"
                    onclick="cancelTryOut('GETapi-admin-logs-activity');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-logs-activity"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/logs/activity</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-logs-activity"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-logs-activity"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="GETapi-admin-logs-activity"
               value="1"
               data-component="query">
    <br>
<p>optional Filter by user ID (vulnerable to SQL injection). Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>action</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="action"                data-endpoint="GETapi-admin-logs-activity"
               value="login"
               data-component="query">
    <br>
<p>optional Filter by action type (vulnerable to SQL injection). Example: <code>login</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="date_from"                data-endpoint="GETapi-admin-logs-activity"
               value="2024-01-01"
               data-component="query">
    <br>
<p>optional Start date for logs (Y-m-d format, vulnerable to SQL injection). Example: <code>2024-01-01</code></p>
            </div>
                </form>

                    <h2 id="admin-POSTapi-admin-users-bulk-manage">Bulk User Role Management</h2>

<p>
</p>

<p>VULNERABILITY 61: Mass user management without safeguards or limits.
VULNERABILITY 62: Dangerous bulk operations logging.
VULNERABILITY 27: Mass role assignment without authorization.
VULNERABILITY 28: Allows deletion of any user including admins.</p>
<p>Performs bulk role updates or deletions without validation.
Can delete admin users and modify any number of users at once.</p>

<span id="example-requests-POSTapi-admin-users-bulk-manage">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/users/bulk-manage" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"operations\": [
        {
            \"user_id\": 1,
            \"role\": \"admin\",
            \"action\": \"update\"
        },
        {
            \"user_id\": 2,
            \"action\": \"delete\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/users/bulk-manage"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "operations": [
        {
            "user_id": 1,
            "role": "admin",
            "action": "update"
        },
        {
            "user_id": 2,
            "action": "delete"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-users-bulk-manage">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;result&quot;: {
        &quot;success&quot;: true,
        &quot;operations_performed&quot;: [
            {
                &quot;user_id&quot;: 1,
                &quot;role&quot;: &quot;admin&quot;,
                &quot;action&quot;: &quot;update&quot;
            },
            {
                &quot;user_id&quot;: 2,
                &quot;action&quot;: &quot;delete&quot;
            }
        ],
        &quot;message&quot;: &quot;Bulk role management completed&quot;
    },
    &quot;affected_users&quot;: [],
    &quot;operation_timestamp&quot;: &quot;2024-01-15T10:00:00.000000Z&quot;,
    &quot;admin_ip&quot;: &quot;192.168.1.1&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-users-bulk-manage" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-users-bulk-manage"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-users-bulk-manage"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-users-bulk-manage" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-users-bulk-manage">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-users-bulk-manage" data-method="POST"
      data-path="api/admin/users/bulk-manage"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-users-bulk-manage', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-users-bulk-manage"
                    onclick="tryItOut('POSTapi-admin-users-bulk-manage');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-users-bulk-manage"
                    onclick="cancelTryOut('POSTapi-admin-users-bulk-manage');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-users-bulk-manage"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/users/bulk-manage</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-users-bulk-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-users-bulk-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>operations</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
<br>
<p>Array of operations to perform.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="operations.0.user_id"                data-endpoint="POSTapi-admin-users-bulk-manage"
               value="1"
               data-component="body">
    <br>
<p>User ID to modify. Example: <code>1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="operations.0.role"                data-endpoint="POSTapi-admin-users-bulk-manage"
               value="admin"
               data-component="body">
    <br>
<p>optional New role (for update action). Example: <code>admin</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>action</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="operations.0.action"                data-endpoint="POSTapi-admin-users-bulk-manage"
               value="update"
               data-component="body">
    <br>
<p>Action type (update, delete). Example: <code>update</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="admin-GETapi-admin-logs-audit">Audit Log Data Management</h2>

<p>
</p>

<p>VULNERABILITY 63: Complete audit log exposure without authorization.
VULNERABILITY 64: Database schema exposure via SHOW TABLES and DESCRIBE.
VULNERABILITY 29: Unrestricted audit log access with SQL injection.
VULNERABILITY 30: Exposes database credentials and system internals.</p>
<p>Returns all audit logs and complete database schema.
Exposes database credentials and admin privileges.</p>

<span id="example-requests-GETapi-admin-logs-audit">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/logs/audit?table=users&amp;action=update" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/logs/audit"
);

const params = {
    "table": "users",
    "action": "update",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-logs-audit">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;audit_logs&quot;: {
        &quot;success&quot;: true,
        &quot;audit_logs&quot;: [],
        &quot;database_info&quot;: {
            &quot;host&quot;: &quot;localhost&quot;,
            &quot;database&quot;: &quot;curameet&quot;,
            &quot;username&quot;: &quot;root&quot;
        }
    },
    &quot;database_schema&quot;: {
        &quot;users&quot;: [
            {
                &quot;Field&quot;: &quot;id&quot;,
                &quot;Type&quot;: &quot;int&quot;,
                &quot;Null&quot;: &quot;NO&quot;
            },
            {
                &quot;Field&quot;: &quot;email&quot;,
                &quot;Type&quot;: &quot;varchar(255)&quot;,
                &quot;Null&quot;: &quot;NO&quot;
            }
        ]
    },
    &quot;admin_privileges&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-logs-audit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-logs-audit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-logs-audit"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-logs-audit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-logs-audit">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-logs-audit" data-method="GET"
      data-path="api/admin/logs/audit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-logs-audit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-logs-audit"
                    onclick="tryItOut('GETapi-admin-logs-audit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-logs-audit"
                    onclick="cancelTryOut('GETapi-admin-logs-audit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-logs-audit"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/logs/audit</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-logs-audit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-logs-audit"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>table</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="table"                data-endpoint="GETapi-admin-logs-audit"
               value="users"
               data-component="query">
    <br>
<p>optional Filter by table name (vulnerable to SQL injection). Example: <code>users</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>action</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="action"                data-endpoint="GETapi-admin-logs-audit"
               value="update"
               data-component="query">
    <br>
<p>optional Filter by action type (vulnerable to SQL injection). Example: <code>update</code></p>
            </div>
                </form>

                    <h2 id="admin-GETapi-admin-logs-api-requests">API Request Logging</h2>

<p>
</p>

<p>VULNERABILITY 65: API request logging exposes sensitive data (passwords, tokens, headers).
VULNERABILITY 66: Current request also logged with all sensitive information.
VULNERABILITY 31: Logs include passwords, authentication tokens, and cookies.
VULNERABILITY 32: No data sanitization or redaction.</p>
<p>Returns API request logs including sensitive request data.
Logs current request with headers, cookies, and session data.</p>

<span id="example-requests-GETapi-admin-logs-api-requests">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/logs/api-requests?endpoint=%2Fapi%2Fauth%2Flogin&amp;method=POST" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/logs/api-requests"
);

const params = {
    "endpoint": "/api/auth/login",
    "method": "POST",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-logs-api-requests">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;api_request_logs&quot;: {
        &quot;success&quot;: true,
        &quot;api_logs&quot;: [],
        &quot;includes_sensitive_data&quot;: true
    },
    &quot;current_request&quot;: {
        &quot;url&quot;: &quot;http://localhost/api/admin/logging&quot;,
        &quot;method&quot;: &quot;GET&quot;,
        &quot;headers&quot;: {
            &quot;Authorization&quot;: &quot;Bearer token123&quot;
        },
        &quot;body&quot;: {},
        &quot;ip&quot;: &quot;192.168.1.1&quot;,
        &quot;user_agent&quot;: &quot;Mozilla/5.0...&quot;,
        &quot;cookies&quot;: {}
    },
    &quot;session_data&quot;: {}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-logs-api-requests" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-logs-api-requests"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-logs-api-requests"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-logs-api-requests" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-logs-api-requests">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-logs-api-requests" data-method="GET"
      data-path="api/admin/logs/api-requests"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-logs-api-requests', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-logs-api-requests"
                    onclick="tryItOut('GETapi-admin-logs-api-requests');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-logs-api-requests"
                    onclick="cancelTryOut('GETapi-admin-logs-api-requests');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-logs-api-requests"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/logs/api-requests</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-logs-api-requests"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-logs-api-requests"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>endpoint</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="endpoint"                data-endpoint="GETapi-admin-logs-api-requests"
               value="/api/auth/login"
               data-component="query">
    <br>
<p>optional Filter by endpoint (vulnerable to SQL injection). Example: <code>/api/auth/login</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>method</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="method"                data-endpoint="GETapi-admin-logs-api-requests"
               value="POST"
               data-component="query">
    <br>
<p>optional Filter by HTTP method (vulnerable to SQL injection). Example: <code>POST</code></p>
            </div>
                </form>

                    <h2 id="admin-GETapi-admin-monitoring-backend">Backend System Monitoring</h2>

<p>
</p>

<p>VULNERABILITY 67: System monitoring without authentication or authorization.
VULNERABILITY 68: Executes dangerous system commands (netstat, ps, cat /etc/passwd).
VULNERABILITY 33: No access control on system monitoring.
VULNERABILITY 34: Command injection vulnerabilities.
VULNERABILITY 35: Information disclosure (database stats, PHP version, server info).</p>
<p>Returns complete system information including network, processes, and users.
Executes system commands without sanitization.</p>

<span id="example-requests-GETapi-admin-monitoring-backend">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/monitoring/backend" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/monitoring/backend"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-monitoring-backend">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;backend_monitoring&quot;: {
        &quot;success&quot;: true,
        &quot;system_info&quot;: {
            &quot;cpu_usage&quot;: &quot;top output&quot;,
            &quot;memory_usage&quot;: &quot;free -m output&quot;,
            &quot;disk_usage&quot;: &quot;df -h output&quot;,
            &quot;database_stats&quot;: [],
            &quot;php_version&quot;: &quot;8.1.0&quot;,
            &quot;server_software&quot;: &quot;nginx/1.21.0&quot;
        }
    },
    &quot;additional_system_info&quot;: {
        &quot;network_connections&quot;: &quot;netstat output&quot;,
        &quot;running_processes&quot;: &quot;ps aux output&quot;,
        &quot;system_users&quot;: &quot;/etc/passwd contents&quot;,
        &quot;environment_variables&quot;: {},
        &quot;loaded_extensions&quot;: [],
        &quot;database_connections&quot;: []
    },
    &quot;monitoring_timestamp&quot;: &quot;2024-01-15T10:00:00.000000Z&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-monitoring-backend" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-monitoring-backend"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-monitoring-backend"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-monitoring-backend" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-monitoring-backend">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-monitoring-backend" data-method="GET"
      data-path="api/admin/monitoring/backend"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-monitoring-backend', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-monitoring-backend"
                    onclick="tryItOut('GETapi-admin-monitoring-backend');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-monitoring-backend"
                    onclick="cancelTryOut('GETapi-admin-monitoring-backend');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-monitoring-backend"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/monitoring/backend</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-monitoring-backend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-monitoring-backend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-GETapi-admin-monitoring-traffic-anomaly">Traffic Anomaly Detection</h2>

<p>
</p>

<p>VULNERABILITY 69: Anomaly detection can be bypassed by manipulating threshold.
VULNERABILITY 70: Exposes all recent traffic data (last 1000 requests).
VULNERABILITY 36: SQL injection in threshold parameter.
VULNERABILITY 37: Exposes security monitoring query and detection logic.</p>
<p>Detects traffic anomalies based on configurable threshold.
Returns all recent traffic including request details.</p>

<span id="example-requests-GETapi-admin-monitoring-traffic-anomaly">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/admin/monitoring/traffic-anomaly?threshold=100" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/monitoring/traffic-anomaly"
);

const params = {
    "threshold": "100",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-monitoring-traffic-anomaly">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;anomaly_detection&quot;: {
        &quot;success&quot;: true,
        &quot;anomalies&quot;: [
            {
                &quot;ip_address&quot;: &quot;192.168.1.1&quot;,
                &quot;request_count&quot;: 150,
                &quot;user_agent&quot;: &quot;Mozilla/5.0...&quot;,
                &quot;endpoint&quot;: &quot;/api/auth/login&quot;
            }
        ],
        &quot;threshold_used&quot;: 100,
        &quot;monitoring_query&quot;: &quot;SELECT ip_address, COUNT(*)...&quot;,
        &quot;detection_bypassed&quot;: true
    },
    &quot;all_recent_traffic&quot;: [],
    &quot;detection_threshold&quot;: 100,
    &quot;can_be_bypassed&quot;: true
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-monitoring-traffic-anomaly" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-monitoring-traffic-anomaly"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-monitoring-traffic-anomaly"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-monitoring-traffic-anomaly" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-monitoring-traffic-anomaly">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-monitoring-traffic-anomaly" data-method="GET"
      data-path="api/admin/monitoring/traffic-anomaly"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-monitoring-traffic-anomaly', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-monitoring-traffic-anomaly"
                    onclick="tryItOut('GETapi-admin-monitoring-traffic-anomaly');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-monitoring-traffic-anomaly"
                    onclick="cancelTryOut('GETapi-admin-monitoring-traffic-anomaly');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-monitoring-traffic-anomaly"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/monitoring/traffic-anomaly</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-monitoring-traffic-anomaly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-monitoring-traffic-anomaly"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>threshold</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="threshold"                data-endpoint="GETapi-admin-monitoring-traffic-anomaly"
               value="100"
               data-component="query">
    <br>
<p>optional Request count threshold for anomaly detection (vulnerable to SQL injection, default: 100). Example: <code>100</code></p>
            </div>
                </form>

                    <h2 id="admin-POSTapi-admin-system-maintenance">System Maintenance Operations</h2>

<p>
</p>

<p>VULNERABILITY 71: Dangerous system maintenance without authorization.
VULNERABILITY 72: Multiple attack vectors (SQL injection, command injection, file inclusion).
VULNERABILITY 38: Can truncate logs, reset passwords, execute arbitrary SQL/commands.</p>
<p>Performs critical system operations without proper authorization.
Supports direct SQL execution, system commands, and file operations.</p>

<span id="example-requests-POSTapi-admin-system-maintenance">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/system/maintenance" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"operation\": \"execute_sql\",
    \"parameters\": {
        \"sql\": \"SELECT * FROM users\",
        \"command\": \"ls -la\",
        \"file\": \"\\/etc\\/passwd\",
        \"filename\": \"backup.sql\"
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/system/maintenance"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "operation": "execute_sql",
    "parameters": {
        "sql": "SELECT * FROM users",
        "command": "ls -la",
        "file": "\/etc\/passwd",
        "filename": "backup.sql"
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-system-maintenance">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;operation&quot;: &quot;execute_sql&quot;,
    &quot;parameters&quot;: {
        &quot;sql&quot;: &quot;SELECT * FROM users&quot;
    },
    &quot;warning&quot;: &quot;Dangerous operation completed&quot;,
    &quot;sql_result&quot;: [],
    &quot;command_output&quot;: &quot;command output here&quot;,
    &quot;file_content&quot;: &quot;file contents here&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-system-maintenance" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-system-maintenance"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-system-maintenance"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-system-maintenance" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-system-maintenance">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-system-maintenance" data-method="POST"
      data-path="api/admin/system/maintenance"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-system-maintenance', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-system-maintenance"
                    onclick="tryItOut('POSTapi-admin-system-maintenance');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-system-maintenance"
                    onclick="cancelTryOut('POSTapi-admin-system-maintenance');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-system-maintenance"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/system/maintenance</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-system-maintenance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-system-maintenance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>operation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="operation"                data-endpoint="POSTapi-admin-system-maintenance"
               value="execute_sql"
               data-component="body">
    <br>
<p>Operation type (clear_logs, reset_passwords, backup_database, execute_sql, system_command, file_operations). Example: <code>execute_sql</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>parameters</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
<br>
<p>optional Operation parameters.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>sql</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="parameters.sql"                data-endpoint="POSTapi-admin-system-maintenance"
               value="SELECT * FROM users"
               data-component="body">
    <br>
<p>optional SQL query to execute (for execute_sql operation). Example: <code>SELECT * FROM users</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>command</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="parameters.command"                data-endpoint="POSTapi-admin-system-maintenance"
               value="ls -la"
               data-component="body">
    <br>
<p>optional System command to execute (for system_command operation). Example: <code>ls -la</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>file</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="parameters.file"                data-endpoint="POSTapi-admin-system-maintenance"
               value="/etc/passwd"
               data-component="body">
    <br>
<p>optional File path to read (for file_operations operation). Example: <code>/etc/passwd</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>filename</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="parameters.filename"                data-endpoint="POSTapi-admin-system-maintenance"
               value="backup.sql"
               data-component="body">
    <br>
<p>optional Backup filename (for backup_database operation). Example: <code>backup.sql</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-users-impersonate">Impersonate User</h2>

<p>
</p>

<p>VULNERABILITY 73: User impersonation without authorization checks.</p>
<p>Allows any user to impersonate any other user by setting session data.
No authorization, audit trail, or time limits.</p>

<span id="example-requests-POSTapi-admin-users-impersonate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/users/impersonate" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"target_user_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/users/impersonate"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "target_user_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-users-impersonate">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;impersonating&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;user@example.com&quot;,
        &quot;role&quot;: &quot;admin&quot;
    },
    &quot;message&quot;: &quot;Now impersonating user&quot;,
    &quot;session_data&quot;: {
        &quot;impersonating&quot;: 1,
        &quot;original_user&quot;: 2
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-users-impersonate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-users-impersonate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-users-impersonate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-users-impersonate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-users-impersonate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-users-impersonate" data-method="POST"
      data-path="api/admin/users/impersonate"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-users-impersonate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-users-impersonate"
                    onclick="tryItOut('POSTapi-admin-users-impersonate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-users-impersonate"
                    onclick="cancelTryOut('POSTapi-admin-users-impersonate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-users-impersonate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/users/impersonate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-users-impersonate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-users-impersonate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>target_user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="target_user_id"                data-endpoint="POSTapi-admin-users-impersonate"
               value="1"
               data-component="body">
    <br>
<p>The ID of user to impersonate (vulnerable to SQL injection). Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-database-backup">Backup Database</h2>

<p>
</p>

<p>VULNERABILITY 74: Database backup exposes sensitive data without authorization.
VULNERABILITY 75: Command injection in mysqldump execution.
VULNERABILITY 76: Backup file accessible via public URL.</p>
<p>Creates database backup and stores in publicly accessible directory.
Exposes database credentials in response.</p>

<span id="example-requests-POSTapi-admin-database-backup">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/database/backup" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"tables\": [
        \"users\",
        \"patients\"
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/database/backup"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "tables": [
        "users",
        "patients"
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-database-backup">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;backup_file&quot;: &quot;backup_2024-01-15_10-00-00.sql&quot;,
    &quot;public_url&quot;: &quot;http://localhost/backups/backup_2024-01-15_10-00-00.sql&quot;,
    &quot;command_executed&quot;: &quot;mysqldump -u root -ppassword database&quot;,
    &quot;database_credentials&quot;: {
        &quot;host&quot;: &quot;localhost&quot;,
        &quot;database&quot;: &quot;curameet&quot;,
        &quot;username&quot;: &quot;root&quot;,
        &quot;password&quot;: &quot;password&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-database-backup" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-database-backup"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-database-backup"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-database-backup" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-database-backup">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-database-backup" data-method="POST"
      data-path="api/admin/database/backup"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-database-backup', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-database-backup"
                    onclick="tryItOut('POSTapi-admin-database-backup');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-database-backup"
                    onclick="cancelTryOut('POSTapi-admin-database-backup');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-database-backup"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/database/backup</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-database-backup"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-database-backup"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tables</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="tables[0]"                data-endpoint="POSTapi-admin-database-backup"
               data-component="body">
        <input type="text" style="display: none"
               name="tables[1]"                data-endpoint="POSTapi-admin-database-backup"
               data-component="body">
    <br>
<p>optional Specific tables to backup (default: all tables).</p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-config-manage">Manage Configuration</h2>

<p>
</p>

<p>VULNERABILITY 77: Direct environment variable manipulation without authorization.
VULNERABILITY 78: Can modify critical system configuration at runtime.</p>
<p>Allows getting, setting, or deleting environment variables.
Exposes all environment configuration including secrets.</p>

<span id="example-requests-POSTapi-admin-config-manage">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/config/manage" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"action\": \"get\",
    \"key\": \"APP_KEY\",
    \"value\": \"base64:newkey123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/config/manage"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "action": "get",
    "key": "APP_KEY",
    "value": "base64:newkey123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-config-manage">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;action&quot;: &quot;get&quot;,
    &quot;key&quot;: &quot;APP_KEY&quot;,
    &quot;value&quot;: &quot;base64:abc123...&quot;,
    &quot;current_env&quot;: {
        &quot;APP_KEY&quot;: &quot;base64:abc123...&quot;,
        &quot;DB_PASSWORD&quot;: &quot;password&quot;,
        &quot;API_SECRET&quot;: &quot;secret123&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;config&quot;: {
        &quot;APP_KEY&quot;: &quot;base64:abc123...&quot;,
        &quot;DB_PASSWORD&quot;: &quot;password&quot;
    },
    &quot;specific_key&quot;: &quot;base64:abc123...&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-config-manage" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-config-manage"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-config-manage"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-config-manage" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-config-manage">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-config-manage" data-method="POST"
      data-path="api/admin/config/manage"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-config-manage', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-config-manage"
                    onclick="tryItOut('POSTapi-admin-config-manage');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-config-manage"
                    onclick="cancelTryOut('POSTapi-admin-config-manage');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-config-manage"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/config/manage</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-config-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-config-manage"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>action</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="action"                data-endpoint="POSTapi-admin-config-manage"
               value="get"
               data-component="body">
    <br>
<p>Action to perform (get, set, delete). Example: <code>get</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="key"                data-endpoint="POSTapi-admin-config-manage"
               value="APP_KEY"
               data-component="body">
    <br>
<p>Configuration key. Example: <code>APP_KEY</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>value</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="value"                data-endpoint="POSTapi-admin-config-manage"
               value="base64:newkey123"
               data-component="body">
    <br>
<p>optional New value (for set action). Example: <code>base64:newkey123</code></p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-artisan-execute">Execute Artisan Command</h2>

<p>
</p>

<p>VULNERABILITY 79: Unrestricted Laravel Artisan command execution.</p>
<p>Allows execution of any Laravel Artisan command without authorization.
Can run migrations, clear cache, generate keys, etc.
Exposes full command output and error traces.</p>

<span id="example-requests-POSTapi-admin-artisan-execute">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/admin/artisan/execute" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"command\": \"migrate:fresh\",
    \"parameters\": {
        \"--seed\": true,
        \"--force\": true
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/admin/artisan/execute"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "command": "migrate:fresh",
    "parameters": {
        "--seed": true,
        "--force": true
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-artisan-execute">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;command&quot;: &quot;migrate:fresh&quot;,
    &quot;parameters&quot;: {
        &quot;--seed&quot;: true,
        &quot;--force&quot;: true
    },
    &quot;exit_code&quot;: 0,
    &quot;output&quot;: &quot;Dropped all tables successfully.\nMigration table created successfully.\nMigrating: 2024_01_01_000000_create_users_table\nMigrated:  2024_01_01_000000_create_users_table&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;error&quot;: &quot;Command not found&quot;,
    &quot;trace&quot;: &quot;Exception trace...&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-artisan-execute" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-artisan-execute"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-artisan-execute"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-artisan-execute" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-artisan-execute">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-artisan-execute" data-method="POST"
      data-path="api/admin/artisan/execute"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-artisan-execute', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-artisan-execute"
                    onclick="tryItOut('POSTapi-admin-artisan-execute');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-artisan-execute"
                    onclick="cancelTryOut('POSTapi-admin-artisan-execute');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-artisan-execute"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/artisan/execute</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-artisan-execute"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-artisan-execute"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>command</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="command"                data-endpoint="POSTapi-admin-artisan-execute"
               value="migrate:fresh"
               data-component="body">
    <br>
<p>Artisan command name. Example: <code>migrate:fresh</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>parameters</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="parameters"                data-endpoint="POSTapi-admin-artisan-execute"
               value=""
               data-component="body">
    <br>
<p>optional Command parameters.</p>
        </div>
        </form>

                <h1 id="appointments">Appointments</h1>

    

                                <h2 id="appointments-POSTapi-appointments-new">Create New Appointment</h2>

<p>
</p>

<p>Creates a new appointment between a patient and doctor.
Validates doctor availability and prevents double booking.</p>
<p>VULNERABILITY: XSS in patient_note (raw, no sanitization)</p>

<span id="example-requests-POSTapi-appointments-new">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/new" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"patient_id\": 1,
    \"doctor_id\": 2,
    \"appointment_time\": \"2024-01-15 10:00:00\",
    \"patient_note\": \"Regular checkup\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/new"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "patient_id": 1,
    "doctor_id": 2,
    "appointment_time": "2024-01-15 10:00:00",
    "patient_note": "Regular checkup"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-new">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;appointment_id&quot;: 1,
    &quot;message&quot;: &quot;Pengecekan berhasil didaftarkan&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Doctor not available at this time&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-new" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-new"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-new"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-new" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-new">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-new" data-method="POST"
      data-path="api/appointments/new"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-new', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-new"
                    onclick="tryItOut('POSTapi-appointments-new');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-new"
                    onclick="cancelTryOut('POSTapi-appointments-new');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-new"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/new</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-new"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-new"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>patient_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patient_id"                data-endpoint="POSTapi-appointments-new"
               value="1"
               data-component="body">
    <br>
<p>The ID of the patient. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-appointments-new"
               value="2"
               data-component="body">
    <br>
<p>The ID of the doctor. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appointment_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="appointment_time"                data-endpoint="POSTapi-appointments-new"
               value="2024-01-15 10:00:00"
               data-component="body">
    <br>
<p>The appointment date and time (Y-m-d H:i:s). Example: <code>2024-01-15 10:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>patient_note</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="patient_note"                data-endpoint="POSTapi-appointments-new"
               value="Regular checkup"
               data-component="body">
    <br>
<p>optional Additional notes from patient (vulnerable to XSS). Example: <code>Regular checkup</code></p>
        </div>
        </form>

                    <h2 id="appointments-POSTapi-appointments--appointmentId--cancel">Cancel Appointment (Patient)</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Allows a patient to cancel their own appointment.
Requires authentication and checks if the appointment belongs to the patient.</p>
<p>VULNERABILITY: XSS in cancellation_reason (raw, no sanitization)</p>

<span id="example-requests-POSTapi-appointments--appointmentId--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/1/cancel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reason\": \"Personal emergency\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/1/cancel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason": "Personal emergency"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments--appointmentId--cancel">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;cancelled_appointment&quot;: {
        &quot;id&quot;: 1,
        &quot;patient_id&quot;: 1,
        &quot;doctor_id&quot;: 2,
        &quot;time_appointment&quot;: &quot;2024-01-15 10:00:00&quot;,
        &quot;status&quot;: &quot;cancelled&quot;,
        &quot;cancellation_reason&quot;: &quot;Personal emergency&quot;,
        &quot;cancelled_by&quot;: &quot;patient&quot;
    },
    &quot;reason&quot;: &quot;Personal emergency&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments--appointmentId--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments--appointmentId--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments--appointmentId--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments--appointmentId--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments--appointmentId--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments--appointmentId--cancel" data-method="POST"
      data-path="api/appointments/{appointmentId}/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments--appointmentId--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments--appointmentId--cancel"
                    onclick="tryItOut('POSTapi-appointments--appointmentId--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments--appointmentId--cancel"
                    onclick="cancelTryOut('POSTapi-appointments--appointmentId--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments--appointmentId--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/{appointmentId}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments--appointmentId--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments--appointmentId--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>appointmentId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointmentId"                data-endpoint="POSTapi-appointments--appointmentId--cancel"
               value="1"
               data-component="url">
    <br>
<p>The ID of the appointment to cancel. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-appointments--appointmentId--cancel"
               value="Personal emergency"
               data-component="body">
    <br>
<p>optional Reason for cancellation (vulnerable to XSS). Example: <code>Personal emergency</code></p>
        </div>
        </form>

                    <h2 id="appointments-POSTapi-appointments-cancel-by-doctor">Cancel Appointment by Doctor ID</h2>

<p>
</p>

<p>Allows a doctor to cancel an appointment.
Verifies that the appointment belongs to the specified doctor.</p>
<p>VULNERABILITY: XSS in cancellation_reason (raw, no sanitization)</p>

<span id="example-requests-POSTapi-appointments-cancel-by-doctor">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/cancel-by-doctor" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"appointment_id\": 1,
    \"reason\": \"Doctor unavailable\",
    \"doctor_id\": 2
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/cancel-by-doctor"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "appointment_id": 1,
    "reason": "Doctor unavailable",
    "doctor_id": 2
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-cancel-by-doctor">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;cancelled_appointment&quot;: {
        &quot;id&quot;: 1,
        &quot;patient_id&quot;: 1,
        &quot;doctor_id&quot;: 2,
        &quot;time_appointment&quot;: &quot;2024-01-15 10:00:00&quot;,
        &quot;status&quot;: &quot;cancelled&quot;,
        &quot;cancellation_reason&quot;: &quot;Doctor unavailable&quot;,
        &quot;cancelled_by&quot;: &quot;doctor&quot;
    },
    &quot;reason&quot;: &quot;Doctor unavailable&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized or appointment not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-cancel-by-doctor" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-cancel-by-doctor"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-cancel-by-doctor"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-cancel-by-doctor" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-cancel-by-doctor">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-cancel-by-doctor" data-method="POST"
      data-path="api/appointments/cancel-by-doctor"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-cancel-by-doctor', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-cancel-by-doctor"
                    onclick="tryItOut('POSTapi-appointments-cancel-by-doctor');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-cancel-by-doctor"
                    onclick="cancelTryOut('POSTapi-appointments-cancel-by-doctor');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-cancel-by-doctor"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/cancel-by-doctor</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-cancel-by-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-cancel-by-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appointment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointment_id"                data-endpoint="POSTapi-appointments-cancel-by-doctor"
               value="1"
               data-component="body">
    <br>
<p>The ID of the appointment. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-appointments-cancel-by-doctor"
               value="Doctor unavailable"
               data-component="body">
    <br>
<p>Reason for cancellation (vulnerable to XSS). Example: <code>Doctor unavailable</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-appointments-cancel-by-doctor"
               value="2"
               data-component="body">
    <br>
<p>The ID of the doctor. Example: <code>2</code></p>
        </div>
        </form>

                    <h2 id="appointments-GETapi-appointments-doctor">Get Appointments by Doctor</h2>

<p>
</p>

<p>Retrieves all appointments for a specific doctor including patient information.</p>

<span id="example-requests-GETapi-appointments-doctor">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/appointments/doctor?doctor_id=2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/doctor"
);

const params = {
    "doctor_id": "2",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-appointments-doctor">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;appointments&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 2,
            &quot;time_appointment&quot;: &quot;2024-01-15 10:00:00&quot;,
            &quot;status&quot;: &quot;pending&quot;,
            &quot;patient_note&quot;: &quot;Regular checkup&quot;,
            &quot;doctor_note&quot;: &quot;Tidak ada&quot;,
            &quot;patient&quot;: {
                &quot;id&quot;: 1,
                &quot;user_id&quot;: 1,
                &quot;NIK&quot;: &quot;1234567890123456&quot;,
                &quot;full_name&quot;: &quot;John Doe&quot;,
                &quot;email&quot;: &quot;patient@example.com&quot;,
                &quot;phone&quot;: &quot;08123456789&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-appointments-doctor" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-appointments-doctor"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-appointments-doctor"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-appointments-doctor" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-appointments-doctor">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-appointments-doctor" data-method="GET"
      data-path="api/appointments/doctor"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-appointments-doctor', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-appointments-doctor"
                    onclick="tryItOut('GETapi-appointments-doctor');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-appointments-doctor"
                    onclick="cancelTryOut('GETapi-appointments-doctor');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-appointments-doctor"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/appointments/doctor</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-appointments-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-appointments-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="GETapi-appointments-doctor"
               value="2"
               data-component="query">
    <br>
<p>The ID of the doctor. Example: <code>2</code></p>
            </div>
                </form>

                    <h2 id="appointments-GETapi-appointments-patient">Get Appointments by Patient</h2>

<p>
</p>

<p>Retrieves all appointments for a specific patient including doctor information.</p>

<span id="example-requests-GETapi-appointments-patient">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/appointments/patient?patient_id=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/patient"
);

const params = {
    "patient_id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-appointments-patient">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;appointments&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 2,
            &quot;time_appointment&quot;: &quot;2024-01-15 10:00:00&quot;,
            &quot;status&quot;: &quot;pending&quot;,
            &quot;patient_note&quot;: &quot;Regular checkup&quot;,
            &quot;doctor_note&quot;: &quot;Tidak ada&quot;,
            &quot;doctor&quot;: {
                &quot;id&quot;: 2,
                &quot;user_id&quot;: 2,
                &quot;str_number&quot;: &quot;STR123456&quot;,
                &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
                &quot;specialist&quot;: &quot;Cardiology&quot;,
                &quot;polyclinic&quot;: &quot;Heart&quot;,
                &quot;available_time&quot;: &quot;08:00-16:00&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-appointments-patient" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-appointments-patient"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-appointments-patient"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-appointments-patient" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-appointments-patient">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-appointments-patient" data-method="GET"
      data-path="api/appointments/patient"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-appointments-patient', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-appointments-patient"
                    onclick="tryItOut('GETapi-appointments-patient');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-appointments-patient"
                    onclick="cancelTryOut('GETapi-appointments-patient');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-appointments-patient"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/appointments/patient</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-appointments-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-appointments-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patient_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patient_id"                data-endpoint="GETapi-appointments-patient"
               value="1"
               data-component="query">
    <br>
<p>The ID of the patient. Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="appointments-POSTapi-appointments-change-schedule-doctor">Change Schedule by Doctor</h2>

<p>
</p>

<p>VULNERABILITY 46: Schedule manipulation without proper validation.
VULNERABILITY 47: Exposes sensitive request data and server time.
Allows a doctor to change appointment schedule.</p>

<span id="example-requests-POSTapi-appointments-change-schedule-doctor">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/change-schedule/doctor" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"new_time\": \"2024-01-16 14:00:00\",
    \"doctor_id\": 2,
    \"appointment_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/change-schedule/doctor"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "new_time": "2024-01-16 14:00:00",
    "doctor_id": 2,
    "appointment_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-change-schedule-doctor">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;result&quot;: {
        &quot;success&quot;: true,
        &quot;updated_appointment&quot;: {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 2,
            &quot;time_appointment&quot;: &quot;2024-01-16 14:00:00&quot;,
            &quot;status&quot;: &quot;pending&quot;
        },
        &quot;new_time&quot;: &quot;2024-01-16 14:00:00&quot;
    },
    &quot;request_data&quot;: {
        &quot;new_time&quot;: &quot;2024-01-16 14:00:00&quot;,
        &quot;doctor_id&quot;: 2,
        &quot;appointment_id&quot;: 1
    },
    &quot;server_time&quot;: &quot;2024-01-15T10:30:00.000000Z&quot;,
    &quot;appointment_id&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-change-schedule-doctor" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-change-schedule-doctor"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-change-schedule-doctor"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-change-schedule-doctor" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-change-schedule-doctor">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-change-schedule-doctor" data-method="POST"
      data-path="api/appointments/change-schedule/doctor"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-change-schedule-doctor', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-change-schedule-doctor"
                    onclick="tryItOut('POSTapi-appointments-change-schedule-doctor');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-change-schedule-doctor"
                    onclick="cancelTryOut('POSTapi-appointments-change-schedule-doctor');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-change-schedule-doctor"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/change-schedule/doctor</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-change-schedule-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-change-schedule-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_time"                data-endpoint="POSTapi-appointments-change-schedule-doctor"
               value="2024-01-16 14:00:00"
               data-component="body">
    <br>
<p>The new appointment time (Y-m-d H:i:s). Example: <code>2024-01-16 14:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-appointments-change-schedule-doctor"
               value="2"
               data-component="body">
    <br>
<p>The ID of the doctor. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appointment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointment_id"                data-endpoint="POSTapi-appointments-change-schedule-doctor"
               value="1"
               data-component="body">
    <br>
<p>The ID of the appointment. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="appointments-POSTapi-appointments-cancel-doctor">Cancel Appointment by Doctor</h2>

<p>
</p>

<p>VULNERABILITY 48: Unrestricted cancellation.
VULNERABILITY 49: Sends notification with sensitive patient data including password.
VULNERABILITY 50: Insecure email sending.
VULNERABILITY 51: Command injection in email sending.</p>

<span id="example-requests-POSTapi-appointments-cancel-doctor">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/cancel/doctor" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"reason\": \"Emergency\",
    \"doctor_id\": 2,
    \"appointment_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/cancel/doctor"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason": "Emergency",
    "doctor_id": 2,
    "appointment_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-cancel-doctor">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;cancelled_appointment&quot;: {
        &quot;id&quot;: 1,
        &quot;patient_id&quot;: 1,
        &quot;doctor_id&quot;: 2,
        &quot;time_appointment&quot;: &quot;2024-01-15 10:00:00&quot;,
        &quot;status&quot;: &quot;cancelled&quot;,
        &quot;cancellation_reason&quot;: &quot;Emergency&quot;,
        &quot;cancelled_by&quot;: &quot;doctor&quot;
    },
    &quot;reason&quot;: &quot;Emergency&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-cancel-doctor" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-cancel-doctor"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-cancel-doctor"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-cancel-doctor" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-cancel-doctor">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-cancel-doctor" data-method="POST"
      data-path="api/appointments/cancel/doctor"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-cancel-doctor', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-cancel-doctor"
                    onclick="tryItOut('POSTapi-appointments-cancel-doctor');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-cancel-doctor"
                    onclick="cancelTryOut('POSTapi-appointments-cancel-doctor');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-cancel-doctor"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/cancel/doctor</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-cancel-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-cancel-doctor"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reason"                data-endpoint="POSTapi-appointments-cancel-doctor"
               value="Emergency"
               data-component="body">
    <br>
<p>Reason for cancellation (vulnerable to XSS). Example: <code>Emergency</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-appointments-cancel-doctor"
               value="2"
               data-component="body">
    <br>
<p>The ID of the doctor. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appointment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointment_id"                data-endpoint="POSTapi-appointments-cancel-doctor"
               value="1"
               data-component="body">
    <br>
<p>The ID of the appointment. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="appointments-POSTapi-appointments-change-schedule-patient">Change Appointment by Patient</h2>

<p>
</p>

<p>VULNERABILITY 46: Schedule manipulation without proper validation or authorization.
Allows patient to change appointment time without proper checks.
Exposes sensitive request data and server information.</p>

<span id="example-requests-POSTapi-appointments-change-schedule-patient">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/change-schedule/patient" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"new_time\": \"2024-01-17 09:00:00\",
    \"patient_id\": 1,
    \"appointment_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/change-schedule/patient"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "new_time": "2024-01-17 09:00:00",
    "patient_id": 1,
    "appointment_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-change-schedule-patient">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;result&quot;: {
        &quot;success&quot;: true,
        &quot;updated_appointment&quot;: {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 2,
            &quot;time_appointment&quot;: &quot;2024-01-17 09:00:00&quot;,
            &quot;status&quot;: &quot;pending&quot;
        },
        &quot;new_time&quot;: &quot;2024-01-17 09:00:00&quot;
    },
    &quot;request_data&quot;: {
        &quot;new_time&quot;: &quot;2024-01-17 09:00:00&quot;,
        &quot;patient_id&quot;: 1,
        &quot;appointment_id&quot;: 1
    },
    &quot;server_time&quot;: &quot;2024-01-15T10:30:00.000000Z&quot;,
    &quot;appointment_id&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-change-schedule-patient" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-change-schedule-patient"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-change-schedule-patient"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-change-schedule-patient" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-change-schedule-patient">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-change-schedule-patient" data-method="POST"
      data-path="api/appointments/change-schedule/patient"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-change-schedule-patient', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-change-schedule-patient"
                    onclick="tryItOut('POSTapi-appointments-change-schedule-patient');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-change-schedule-patient"
                    onclick="cancelTryOut('POSTapi-appointments-change-schedule-patient');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-change-schedule-patient"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/change-schedule/patient</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-change-schedule-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-change-schedule-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_time"                data-endpoint="POSTapi-appointments-change-schedule-patient"
               value="2024-01-17 09:00:00"
               data-component="body">
    <br>
<p>The new appointment time (Y-m-d H:i:s). Example: <code>2024-01-17 09:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>patient_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patient_id"                data-endpoint="POSTapi-appointments-change-schedule-patient"
               value="1"
               data-component="body">
    <br>
<p>The ID of the patient. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>appointment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointment_id"                data-endpoint="POSTapi-appointments-change-schedule-patient"
               value="1"
               data-component="body">
    <br>
<p>The ID of the appointment. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="appointments-POSTapi-appointments-bulk-update">Bulk Update Appointments</h2>

<p>
</p>

<p>VULNERABILITY 54: Bulk operations without rate limits or proper validation.
Allows updating multiple appointments at once with SQL injection vulnerability.
No authentication or authorization required.
No limit on number of appointments that can be updated.</p>

<span id="example-requests-POSTapi-appointments-bulk-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/appointments/bulk-update" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"appointments\": [
        {
            \"id\": 1,
            \"status\": \"confirmed\",
            \"new_time\": \"2024-01-20 10:00:00\",
            \"patient_note\": \"Updated by admin\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/appointments/bulk-update"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "appointments": [
        {
            "id": 1,
            "status": "confirmed",
            "new_time": "2024-01-20 10:00:00",
            "patient_note": "Updated by admin"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-appointments-bulk-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;updated_appointments&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;status&quot;: &quot;confirmed&quot;,
            &quot;new_time&quot;: &quot;2024-01-20 10:00:00&quot;,
            &quot;patient_note&quot;: &quot;Updated by admin&quot;
        }
    ],
    &quot;message&quot;: &quot;Bulk update completed&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-appointments-bulk-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-appointments-bulk-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-appointments-bulk-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-appointments-bulk-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-appointments-bulk-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-appointments-bulk-update" data-method="POST"
      data-path="api/appointments/bulk-update"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-appointments-bulk-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-appointments-bulk-update"
                    onclick="tryItOut('POSTapi-appointments-bulk-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-appointments-bulk-update"
                    onclick="cancelTryOut('POSTapi-appointments-bulk-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-appointments-bulk-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/appointments/bulk-update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-appointments-bulk-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-appointments-bulk-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>appointments</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
 &nbsp;
<br>
<p>Array of appointment updates.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="appointments.0.id"                data-endpoint="POSTapi-appointments-bulk-update"
               value="1"
               data-component="body">
    <br>
<p>Appointment ID (vulnerable to SQL injection). Example: <code>1</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="appointments.0.status"                data-endpoint="POSTapi-appointments-bulk-update"
               value="confirmed"
               data-component="body">
    <br>
<p>New status (vulnerable to SQL injection). Example: <code>confirmed</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>new_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="appointments.0.new_time"                data-endpoint="POSTapi-appointments-bulk-update"
               value="2024-01-20 10:00:00"
               data-component="body">
    <br>
<p>optional New appointment time (vulnerable to SQL injection). Example: <code>2024-01-20 10:00:00</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>patient_note</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="appointments.0.patient_note"                data-endpoint="POSTapi-appointments-bulk-update"
               value="Updated by admin"
               data-component="body">
    <br>
<p>optional Patient notes (vulnerable to SQL injection &amp; XSS). Example: <code>Updated by admin</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>doctor_note</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="appointments.0.doctor_note"                data-endpoint="POSTapi-appointments-bulk-update"
               value="Reviewed"
               data-component="body">
    <br>
<p>optional Doctor notes (vulnerable to SQL injection &amp; XSS). Example: <code>Reviewed</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                <h1 id="authentication">Authentication</h1>

    

                                <h2 id="authentication-POSTapi-auth-login">User Login</h2>

<p>
</p>

<p>VULNERABILITY 25: No input validation, sanitization, or rate limiting.
VULNERABILITY 1: SQL Injection vulnerability in login query.
VULNERABILITY 3: Information disclosure in logs (password logged).
VULNERABILITY 5: No rate limiting on login attempts.</p>
<p>Authenticates user with email, password, and role.
Returns user information and Bearer token.</p>

<span id="example-requests-POSTapi-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@test.com\",
    \"password\": \"password123\",
    \"role\": \"patient\",
    \"remember_me\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "user@test.com",
    "password": "password123",
    "role": "patient",
    "remember_me": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-login">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;user@test.com&quot;,
        &quot;role&quot;: &quot;patient&quot;
    },
    &quot;token&quot;: &quot;abc123def456...&quot;,
    &quot;token_type&quot;: &quot;Bearer&quot;,
    &quot;expires_in&quot;: 86400
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid credentials&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-login" data-method="POST"
      data-path="api/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-login"
                    onclick="tryItOut('POSTapi-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-login"
                    onclick="cancelTryOut('POSTapi-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-login"
               value="user@test.com"
               data-component="body">
    <br>
<p>User email address. Example: <code>user@test.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-auth-login"
               value="password123"
               data-component="body">
    <br>
<p>User password (plain text). Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-auth-login"
               value="patient"
               data-component="body">
    <br>
<p>optional User role (patient, doctor, admin). Defaults to patient. Example: <code>patient</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>remember_me</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-auth-login" style="display: none">
            <input type="radio" name="remember_me"
                   value="true"
                   data-endpoint="POSTapi-auth-login"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-auth-login" style="display: none">
            <input type="radio" name="remember_me"
                   value="false"
                   data-endpoint="POSTapi-auth-login"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Enable remember me functionality. Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-auth-register">User Registration</h2>

<p>
</p>

<p>VULNERABILITY 26: Verbose error messages expose internal system information.</p>
<p>Registers a new user (patient, doctor, or admin).
Creates associated patient or doctor record based on role.</p>

<span id="example-requests-POSTapi-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"email\": \"newuser@test.com\",
    \"password\": \"password123\",
    \"password_confirmation\": \"password123\",
    \"role\": \"patient\\n\\nFor Patient (when role=patient):\",
    \"NIK\": \"1234567890123456\",
    \"full_name\": \"Dr. Jane Smith\",
    \"picture\": \"avatar.jpg\",
    \"allergies\": \"Peanuts, Dust\",
    \"disease_histories\": \"Asthma\\n\\nFor Doctor (when role=doctor):\",
    \"str_number\": \"STR123456\",
    \"specialist\": \"Cardiology\",
    \"polyclinic\": \"Heart\",
    \"available_time\": \"08:00-16:00\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "email": "newuser@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "patient\n\nFor Patient (when role=patient):",
    "NIK": "1234567890123456",
    "full_name": "Dr. Jane Smith",
    "picture": "avatar.jpg",
    "allergies": "Peanuts, Dust",
    "disease_histories": "Asthma\n\nFor Doctor (when role=doctor):",
    "str_number": "STR123456",
    "specialist": "Cardiology",
    "polyclinic": "Heart",
    "available_time": "08:00-16:00"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-register">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;User registered successfully&quot;,
    &quot;user_id&quot;: 1
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;errors&quot;: [
        &quot;The email has already been taken.&quot;,
        &quot;The password confirmation does not match.&quot;
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Registration failed&quot;,
    &quot;error&quot;: &quot;SQLSTATE[23505]: Unique violation...&quot;,
    &quot;trace&quot;: &quot;#0 /var/www/html/app/Services/AuthService.php(123): ...&quot;,
    &quot;file&quot;: &quot;/var/www/html/app/Services/AuthService.php&quot;,
    &quot;line&quot;: 123
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-register" data-method="POST"
      data-path="api/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-register"
                    onclick="tryItOut('POSTapi-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-register"
                    onclick="cancelTryOut('POSTapi-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-auth-register"
               value="John Doe"
               data-component="body">
    <br>
<p>Full name of the user. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-register"
               value="newuser@test.com"
               data-component="body">
    <br>
<p>Email address (must be unique). Example: <code>newuser@test.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-auth-register"
               value="password123"
               data-component="body">
    <br>
<p>Password (minimum 8 characters). Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-auth-register"
               value="password123"
               data-component="body">
    <br>
<p>Password confirmation. Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-auth-register"
               value="patient

For Patient (when role=patient):"
               data-component="body">
    <br>
<p>optional User role (patient, doctor, admin). Defaults to patient. Example: `patient</p>
<p>For Patient (when role=patient):`</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>NIK</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="NIK"                data-endpoint="POSTapi-auth-register"
               value="1234567890123456"
               data-component="body">
    <br>
<p>optional National ID number (max 20 chars, unique). Example: <code>1234567890123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="POSTapi-auth-register"
               value="Dr. Jane Smith"
               data-component="body">
    <br>
<p>Full name for doctor record. Example: <code>Dr. Jane Smith</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>picture</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="picture"                data-endpoint="POSTapi-auth-register"
               value="avatar.jpg"
               data-component="body">
    <br>
<p>optional Profile picture path. Example: <code>avatar.jpg</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>allergies</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="allergies"                data-endpoint="POSTapi-auth-register"
               value="Peanuts, Dust"
               data-component="body">
    <br>
<p>optional Patient allergies. Example: <code>Peanuts, Dust</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>disease_histories</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="disease_histories"                data-endpoint="POSTapi-auth-register"
               value="Asthma

For Doctor (when role=doctor):"
               data-component="body">
    <br>
<p>optional Patient disease history. Example: `Asthma</p>
<p>For Doctor (when role=doctor):`</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>str_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="str_number"                data-endpoint="POSTapi-auth-register"
               value="STR123456"
               data-component="body">
    <br>
<p>Doctor registration number (unique). Example: <code>STR123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>specialist</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="specialist"                data-endpoint="POSTapi-auth-register"
               value="Cardiology"
               data-component="body">
    <br>
<p>Medical specialization. Example: <code>Cardiology</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>polyclinic</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="polyclinic"                data-endpoint="POSTapi-auth-register"
               value="Heart"
               data-component="body">
    <br>
<p>Polyclinic/Department. Example: <code>Heart</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>available_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="available_time"                data-endpoint="POSTapi-auth-register"
               value="08:00-16:00"
               data-component="body">
    <br>
<p>optional Available time schedule. Example: <code>08:00-16:00</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-auth-password-reset">Reset Password</h2>

<p>
</p>

<p>VULNERABILITY 27: No authentication required for sensitive operations.
VULNERABILITY 7: Weak password reset implementation.
VULNERABILITY 9: Plain text password in email/logs.</p>
<p>Allows anyone to reset any user's password without verification.</p>

<span id="example-requests-POSTapi-auth-password-reset">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/password/reset" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"user@test.com\",
    \"new_password\": \"newpassword123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/password/reset"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "user@test.com",
    "new_password": "newpassword123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-password-reset">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Password reset successful&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-password-reset" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-password-reset"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-password-reset"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-password-reset" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-password-reset">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-password-reset" data-method="POST"
      data-path="api/auth/password/reset"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-password-reset', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-password-reset"
                    onclick="tryItOut('POSTapi-auth-password-reset');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-password-reset"
                    onclick="cancelTryOut('POSTapi-auth-password-reset');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-password-reset"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/password/reset</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-password-reset"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-password-reset"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-password-reset"
               value="user@test.com"
               data-component="body">
    <br>
<p>Email of the user whose password to reset. Example: <code>user@test.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="new_password"                data-endpoint="POSTapi-auth-password-reset"
               value="newpassword123"
               data-component="body">
    <br>
<p>optional New password (if not provided, defaults to 'temp123'). Example: <code>newpassword123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-auth-email-check">Check Email Availability</h2>

<p>
</p>

<p>VULNERABILITY 18: User enumeration attack.
Allows attackers to determine which emails are registered.</p>
<p>Checks if an email address is already registered in the system.</p>

<span id="example-requests-POSTapi-auth-email-check">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/email/check" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"test@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/email/check"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "test@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-email-check">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;exists&quot;: true,
    &quot;message&quot;: &quot;Email already registered&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;exists&quot;: false,
    &quot;message&quot;: &quot;Email available&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-email-check" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-email-check"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-email-check"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-email-check" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-email-check">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-email-check" data-method="POST"
      data-path="api/auth/email/check"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-email-check', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-email-check"
                    onclick="tryItOut('POSTapi-auth-email-check');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-email-check"
                    onclick="cancelTryOut('POSTapi-auth-email-check');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-email-check"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/email/check</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-email-check"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-email-check"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-email-check"
               value="test@example.com"
               data-component="body">
    <br>
<p>Email address to check. Example: <code>test@example.com</code></p>
        </div>
        </form>

                    <h2 id="authentication-GETapi-auth-token-verify">Verify Token</h2>

<p>
</p>

<p>Checks if a token is valid and not expired.
Returns user information if token is valid.</p>

<span id="example-requests-GETapi-auth-token-verify">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/auth/token/verify" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/token/verify"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-auth-token-verify">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;valid&quot;: true,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;user@test.com&quot;,
        &quot;role&quot;: &quot;patient&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;valid&quot;: false,
    &quot;message&quot;: &quot;Invalid or expired token&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Token not provided&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-auth-token-verify" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-auth-token-verify"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-token-verify"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-auth-token-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-token-verify">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-auth-token-verify" data-method="GET"
      data-path="api/auth/token/verify"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-token-verify', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-auth-token-verify"
                    onclick="tryItOut('GETapi-auth-token-verify');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-auth-token-verify"
                    onclick="cancelTryOut('GETapi-auth-token-verify');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-auth-token-verify"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/auth/token/verify</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization"                data-endpoint="GETapi-auth-token-verify"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-auth-token-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-auth-token-verify"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-GETapi-auth-user">Get Current Authenticated User</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the currently authenticated user information.
Supports authentication via Bearer token or session.</p>

<span id="example-requests-GETapi-auth-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/auth/user" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/user"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-auth-user">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;John Doe&quot;,
        &quot;email&quot;: &quot;user@test.com&quot;,
        &quot;role&quot;: &quot;patient&quot;,
        &quot;token&quot;: &quot;abc123...&quot;,
        &quot;expires_at&quot;: &quot;2024-01-16 10:00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Not authenticated&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-auth-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-auth-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-auth-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-auth-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-auth-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-auth-user" data-method="GET"
      data-path="api/auth/user"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-auth-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-auth-user"
                    onclick="tryItOut('GETapi-auth-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-auth-user"
                    onclick="cancelTryOut('GETapi-auth-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-auth-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/auth/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-auth-user"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-auth-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-auth-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-POSTapi-auth-logout">User Logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Logs out the user by invalidating their token and clearing session.</p>

<span id="example-requests-POSTapi-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/logout" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/logout"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Logged out successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-logout" data-method="POST"
      data-path="api/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-logout"
                    onclick="tryItOut('POSTapi-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-logout"
                    onclick="cancelTryOut('POSTapi-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-logout"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-POSTapi-auth-token-refresh">Refresh Token</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Refreshes an existing token before it expires.
Returns a new token with extended expiration (24 hours).</p>

<span id="example-requests-POSTapi-auth-token-refresh">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/token/refresh" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/token/refresh"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-token-refresh">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;token&quot;: &quot;new_abc123def456...&quot;,
    &quot;token_type&quot;: &quot;Bearer&quot;,
    &quot;expires_in&quot;: 86400
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid or expired token&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Token not provided&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-token-refresh" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-token-refresh"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-token-refresh"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-token-refresh" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-token-refresh">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-token-refresh" data-method="POST"
      data-path="api/auth/token/refresh"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-token-refresh', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-token-refresh"
                    onclick="tryItOut('POSTapi-auth-token-refresh');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-token-refresh"
                    onclick="cancelTryOut('POSTapi-auth-token-refresh');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-token-refresh"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/token/refresh</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-token-refresh"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-token-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-token-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-POSTapi-auth-password-change">Change Password</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>VULNERABILITY 19: No old password verification (weak implementation).</p>
<p>Allows authenticated user to change their password.
Requires authentication but doesn't verify old password properly.</p>

<span id="example-requests-POSTapi-auth-password-change">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/password/change" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"old_password\": \"oldpassword123\",
    \"new_password\": \"newpassword123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/password/change"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "old_password": "oldpassword123",
    "new_password": "newpassword123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-password-change">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Password changed&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-password-change" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-password-change"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-password-change"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-password-change" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-password-change">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-password-change" data-method="POST"
      data-path="api/auth/password/change"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-password-change', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-password-change"
                    onclick="tryItOut('POSTapi-auth-password-change');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-password-change"
                    onclick="cancelTryOut('POSTapi-auth-password-change');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-password-change"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/password/change</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-password-change"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-password-change"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-password-change"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>old_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="old_password"                data-endpoint="POSTapi-auth-password-change"
               value="oldpassword123"
               data-component="body">
    <br>
<p>Current password. Example: <code>oldpassword123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>new_password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="new_password"                data-endpoint="POSTapi-auth-password-change"
               value="newpassword123"
               data-component="body">
    <br>
<p>New password. Example: <code>newpassword123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-auth-profile-update">Update User Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>VULNERABILITY 22: No CSRF protection, allows role manipulation.</p>
<p>Allows authenticated user to update their profile information.
Vulnerable to privilege escalation through role manipulation.</p>

<span id="example-requests-POSTapi-auth-profile-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/auth/profile/update" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Jane Doe\",
    \"email\": \"newemail@test.com\",
    \"role\": \"admin\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/auth/profile/update"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Jane Doe",
    "email": "newemail@test.com",
    "role": "admin"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-auth-profile-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-auth-profile-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-auth-profile-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-auth-profile-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-auth-profile-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-auth-profile-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-auth-profile-update" data-method="POST"
      data-path="api/auth/profile/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-auth-profile-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-auth-profile-update"
                    onclick="tryItOut('POSTapi-auth-profile-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-auth-profile-update"
                    onclick="cancelTryOut('POSTapi-auth-profile-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-auth-profile-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/auth/profile/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-auth-profile-update"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-auth-profile-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-auth-profile-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-auth-profile-update"
               value="Jane Doe"
               data-component="body">
    <br>
<p>Updated name. Example: <code>Jane Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-auth-profile-update"
               value="newemail@test.com"
               data-component="body">
    <br>
<p>Updated email address. Example: <code>newemail@test.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-auth-profile-update"
               value="admin"
               data-component="body">
    <br>
<p>optional Updated role (allows privilege escalation). Example: <code>admin</code></p>
        </div>
        </form>

                <h1 id="doctors">Doctors</h1>

    

                                <h2 id="doctors-GETapi-doctors">List All Doctors</h2>

<p>
</p>

<p>Returns a list of all doctors with basic information.</p>

<span id="example-requests-GETapi-doctors">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/doctors" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-doctors">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctors&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;str_number&quot;: &quot;STR123456&quot;,
            &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;specialist&quot;: &quot;Cardiology&quot;,
            &quot;available_time&quot;: &quot;08:00-16:00&quot;,
            &quot;polyclinic&quot;: &quot;Heart&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;str_number&quot;: &quot;STR789012&quot;,
            &quot;full_name&quot;: &quot;Dr. John Doe&quot;,
            &quot;specialist&quot;: &quot;Dermatology&quot;,
            &quot;available_time&quot;: &quot;09:00-17:00&quot;,
            &quot;polyclinic&quot;: &quot;Skin&quot;
        }
    ],
    &quot;count&quot;: 2
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-doctors" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-doctors"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-doctors"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-doctors" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-doctors">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-doctors" data-method="GET"
      data-path="api/doctors"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-doctors', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-doctors"
                    onclick="tryItOut('GETapi-doctors');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-doctors"
                    onclick="cancelTryOut('GETapi-doctors');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-doctors"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/doctors</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-doctors"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-doctors"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="doctors-GETapi-doctors-search">Search Doctors by Name</h2>

<p>
</p>

<p>Searches for doctors by their full name (partial match supported).</p>

<span id="example-requests-GETapi-doctors-search">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/doctors/search?name=Jane" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/search"
);

const params = {
    "name": "Jane",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-doctors-search">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctors&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;user_id&quot;: 2,
            &quot;str_number&quot;: &quot;STR123456&quot;,
            &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;specialist&quot;: &quot;Cardiology&quot;,
            &quot;polyclinic&quot;: &quot;Heart&quot;,
            &quot;available_time&quot;: &quot;08:00-16:00&quot;
        }
    ],
    &quot;count&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-doctors-search" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-doctors-search"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-doctors-search"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-doctors-search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-doctors-search">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-doctors-search" data-method="GET"
      data-path="api/doctors/search"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-doctors-search', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-doctors-search"
                    onclick="tryItOut('GETapi-doctors-search');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-doctors-search"
                    onclick="cancelTryOut('GETapi-doctors-search');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-doctors-search"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/doctors/search</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-doctors-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-doctors-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-doctors-search"
               value="Jane"
               data-component="query">
    <br>
<p>Part or full name of the doctor to search. Example: <code>Jane</code></p>
            </div>
                </form>

                    <h2 id="doctors-GETapi-doctors--doctorId-">Get Doctor by ID</h2>

<p>
</p>

<p>Retrieves doctor information by doctor ID.</p>

<span id="example-requests-GETapi-doctors--doctorId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/doctors/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-doctors--doctorId-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctor&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 2,
        &quot;str_number&quot;: &quot;STR123456&quot;,
        &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
        &quot;specialist&quot;: &quot;Cardiology&quot;,
        &quot;polyclinic&quot;: &quot;Heart&quot;,
        &quot;available_time&quot;: &quot;08:00-16:00&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;email&quot;: &quot;doctor@test.com&quot;,
            &quot;role&quot;: &quot;doctor&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Doctor not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-doctors--doctorId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-doctors--doctorId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-doctors--doctorId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-doctors--doctorId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-doctors--doctorId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-doctors--doctorId-" data-method="GET"
      data-path="api/doctors/{doctorId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-doctors--doctorId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-doctors--doctorId-"
                    onclick="tryItOut('GETapi-doctors--doctorId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-doctors--doctorId-"
                    onclick="cancelTryOut('GETapi-doctors--doctorId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-doctors--doctorId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/doctors/{doctorId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-doctors--doctorId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-doctors--doctorId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>doctorId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctorId"                data-endpoint="GETapi-doctors--doctorId-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the doctor. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="doctors-GETapi-doctors-user--userId-">Get Doctor by User ID</h2>

<p>
</p>

<p>Retrieves doctor information by user ID.</p>

<span id="example-requests-GETapi-doctors-user--userId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/doctors/user/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/user/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-doctors-user--userId-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctor&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 2,
        &quot;str_number&quot;: &quot;STR123456&quot;,
        &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
        &quot;specialist&quot;: &quot;Cardiology&quot;,
        &quot;polyclinic&quot;: &quot;Heart&quot;,
        &quot;available_time&quot;: &quot;08:00-16:00&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;email&quot;: &quot;doctor@test.com&quot;,
            &quot;role&quot;: &quot;doctor&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Doctor not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-doctors-user--userId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-doctors-user--userId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-doctors-user--userId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-doctors-user--userId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-doctors-user--userId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-doctors-user--userId-" data-method="GET"
      data-path="api/doctors/user/{userId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-doctors-user--userId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-doctors-user--userId-"
                    onclick="tryItOut('GETapi-doctors-user--userId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-doctors-user--userId-"
                    onclick="cancelTryOut('GETapi-doctors-user--userId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-doctors-user--userId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/doctors/user/{userId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-doctors-user--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-doctors-user--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>userId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="userId"                data-endpoint="GETapi-doctors-user--userId-"
               value="2"
               data-component="url">
    <br>
<p>The user ID of the doctor. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="doctors-POSTapi-doctors-medical-records-view">View Medical Records (Doctor)</h2>

<p>
</p>

<p>VULNERABILITY 44: Mass data exposure without authorization.
VULNERABILITY 45: Additional sensitive data exposure (passwords, tokens).</p>
<p>Allows viewing medical records without proper authorization.
Exposes sensitive patient information including passwords.</p>

<span id="example-requests-POSTapi-doctors-medical-records-view">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/doctors/medical-records/view?doctor_id=1&amp;patient_id=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/medical-records/view"
);

const params = {
    "doctor_id": "1",
    "patient_id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-doctors-medical-records-view">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;records&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 1,
            &quot;disease_name&quot;: &quot;Hypertension&quot;,
            &quot;notes&quot;: &quot;Regular checkup&quot;,
            &quot;created_at&quot;: &quot;2024-01-15 10:00:00&quot;,
            &quot;sensitive_info&quot;: {
                &quot;password&quot;: &quot;password123&quot;,
                &quot;remember_token&quot;: &quot;abc123def456&quot;,
                &quot;allergies&quot;: &quot;Peanuts&quot;,
                &quot;disease_histories&quot;: &quot;Asthma&quot;
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-doctors-medical-records-view" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-doctors-medical-records-view"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-doctors-medical-records-view"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-doctors-medical-records-view" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-doctors-medical-records-view">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-doctors-medical-records-view" data-method="POST"
      data-path="api/doctors/medical-records/view"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-doctors-medical-records-view', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-doctors-medical-records-view"
                    onclick="tryItOut('POSTapi-doctors-medical-records-view');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-doctors-medical-records-view"
                    onclick="cancelTryOut('POSTapi-doctors-medical-records-view');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-doctors-medical-records-view"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/doctors/medical-records/view</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-doctors-medical-records-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-doctors-medical-records-view"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-doctors-medical-records-view"
               value="1"
               data-component="query">
    <br>
<p>The ID of the doctor. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patient_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patient_id"                data-endpoint="POSTapi-doctors-medical-records-view"
               value="1"
               data-component="query">
    <br>
<p>The ID of the patient. Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="doctors-POSTapi-doctors-patients--patientId--export">Export Patient Data</h2>

<p>
</p>

<p>VULNERABILITY 52: Patient data export without authorization.
VULNERABILITY 53: Direct file system access with predictable paths.</p>
<p>Exports all patient data including sensitive information (passwords, tokens).
Creates file in /tmp with predictable naming pattern.
No authorization or data minimization.</p>

<span id="example-requests-POSTapi-doctors-patients--patientId--export">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/doctors/patients/1/export" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/patients/1/export"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-doctors-patients--patientId--export">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;export_file&quot;: &quot;patient_export_1_1705315200.json&quot;,
    &quot;file_path&quot;: &quot;/tmp/patient_export_1_1705315200.json&quot;,
    &quot;patient_data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;user_id&quot;: 1,
            &quot;NIK&quot;: &quot;1234567890123456&quot;,
            &quot;full_name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;patient@example.com&quot;,
            &quot;password&quot;: &quot;password123&quot;,
            &quot;remember_token&quot;: &quot;abc123def456&quot;,
            &quot;allergies&quot;: &quot;Peanuts&quot;,
            &quot;disease_histories&quot;: &quot;Asthma&quot;,
            &quot;all_diseases&quot;: &quot;Hypertension,Diabetes&quot;,
            &quot;all_notes&quot;: &quot;Regular checkup,Follow-up appointment&quot;
        }
    ],
    &quot;exported_at&quot;: &quot;2024-01-15T10:00:00.000000Z&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-doctors-patients--patientId--export" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-doctors-patients--patientId--export"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-doctors-patients--patientId--export"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-doctors-patients--patientId--export" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-doctors-patients--patientId--export">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-doctors-patients--patientId--export" data-method="POST"
      data-path="api/doctors/patients/{patientId}/export"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-doctors-patients--patientId--export', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-doctors-patients--patientId--export"
                    onclick="tryItOut('POSTapi-doctors-patients--patientId--export');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-doctors-patients--patientId--export"
                    onclick="cancelTryOut('POSTapi-doctors-patients--patientId--export');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-doctors-patients--patientId--export"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/doctors/patients/{patientId}/export</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-doctors-patients--patientId--export"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-doctors-patients--patientId--export"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patientId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patientId"                data-endpoint="POSTapi-doctors-patients--patientId--export"
               value="1"
               data-component="url">
    <br>
<p>The ID of the patient to export. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="doctors-POSTapi-doctors-schedule-update">Update Doctor Schedule</h2>

<p>
</p>

<p>VULNERABILITY 55: Doctor schedule manipulation without authorization.
VULNERABILITY 56: Exposes doctor credentials (password) in response.</p>
<p>Allows anyone to update any doctor's schedule without authorization.
Returns sensitive doctor information including password.</p>

<span id="example-requests-POSTapi-doctors-schedule-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/doctors/schedule/update" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"doctor_id\": 1,
    \"schedule\": \"Morning shift only\",
    \"available_time\": \"08:00-12:00\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/schedule/update"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "doctor_id": 1,
    "schedule": "Morning shift only",
    "available_time": "08:00-12:00"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-doctors-schedule-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctor_info&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;user_id&quot;: 2,
            &quot;str_number&quot;: &quot;STR123456&quot;,
            &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;specialist&quot;: &quot;Cardiology&quot;,
            &quot;polyclinic&quot;: &quot;Heart&quot;,
            &quot;available_time&quot;: &quot;08:00-12:00&quot;,
            &quot;email&quot;: &quot;doctor@test.com&quot;,
            &quot;password&quot;: &quot;password123&quot;
        }
    ],
    &quot;new_schedule&quot;: &quot;Morning shift only&quot;,
    &quot;updated_time&quot;: &quot;08:00-12:00&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-doctors-schedule-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-doctors-schedule-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-doctors-schedule-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-doctors-schedule-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-doctors-schedule-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-doctors-schedule-update" data-method="POST"
      data-path="api/doctors/schedule/update"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-doctors-schedule-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-doctors-schedule-update"
                    onclick="tryItOut('POSTapi-doctors-schedule-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-doctors-schedule-update"
                    onclick="cancelTryOut('POSTapi-doctors-schedule-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-doctors-schedule-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/doctors/schedule/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-doctors-schedule-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-doctors-schedule-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="doctor_id"                data-endpoint="POSTapi-doctors-schedule-update"
               value="1"
               data-component="body">
    <br>
<p>The ID of the doctor. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>schedule</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="schedule"                data-endpoint="POSTapi-doctors-schedule-update"
               value="Morning shift only"
               data-component="body">
    <br>
<p>optional New schedule description. Example: <code>Morning shift only</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>available_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="available_time"                data-endpoint="POSTapi-doctors-schedule-update"
               value="08:00-12:00"
               data-component="body">
    <br>
<p>New available time range. Example: <code>08:00-12:00</code></p>
        </div>
        </form>

                    <h2 id="doctors-GETapi-doctors-profile-now">Get Current Doctor Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the authenticated doctor's profile information.
Requires valid authentication token.</p>

<span id="example-requests-GETapi-doctors-profile-now">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/doctors/profile/now" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/doctors/profile/now"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-doctors-profile-now">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;doctor&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 2,
        &quot;str_number&quot;: &quot;STR123456&quot;,
        &quot;full_name&quot;: &quot;Dr. Jane Smith&quot;,
        &quot;specialist&quot;: &quot;Cardiology&quot;,
        &quot;polyclinic&quot;: &quot;Heart&quot;,
        &quot;available_time&quot;: &quot;08:00-16:00&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;email&quot;: &quot;doctor@test.com&quot;,
            &quot;role&quot;: &quot;doctor&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Token not provided&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid or expired token&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Doctor not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-doctors-profile-now" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-doctors-profile-now"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-doctors-profile-now"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-doctors-profile-now" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-doctors-profile-now">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-doctors-profile-now" data-method="GET"
      data-path="api/doctors/profile/now"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-doctors-profile-now', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-doctors-profile-now"
                    onclick="tryItOut('GETapi-doctors-profile-now');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-doctors-profile-now"
                    onclick="cancelTryOut('GETapi-doctors-profile-now');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-doctors-profile-now"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/doctors/profile/now</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-doctors-profile-now"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-doctors-profile-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-doctors-profile-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-OPTIONSapi--any-">OPTIONS api/{any}</h2>

<p>
</p>



<span id="example-requests-OPTIONSapi--any-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request OPTIONS \
    "http://localhost/api/|{+-0p" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/|{+-0p"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "OPTIONS",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-OPTIONSapi--any-">
</span>
<span id="execution-results-OPTIONSapi--any-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-OPTIONSapi--any-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-OPTIONSapi--any-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-OPTIONSapi--any-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-OPTIONSapi--any-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-OPTIONSapi--any-" data-method="OPTIONS"
      data-path="api/{any}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('OPTIONSapi--any-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-OPTIONSapi--any-"
                    onclick="tryItOut('OPTIONSapi--any-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-OPTIONSapi--any-"
                    onclick="cancelTryOut('OPTIONSapi--any-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-OPTIONSapi--any-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-grey">OPTIONS</small>
            <b><code>api/{any}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="OPTIONSapi--any-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="OPTIONSapi--any-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>any</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="any"                data-endpoint="OPTIONSapi--any-"
               value="|{+-0p"
               data-component="url">
    <br>
<p>Example: <code>|{+-0p</code></p>
            </div>
                    </form>

                <h1 id="medical-records">Medical Records</h1>

    

                                <h2 id="medical-records-GETapi-medical-records-patient">Get Medical Records by Patient ID</h2>

<p>
</p>

<p>VULNERABILITY 40: No authorization check - anyone can view any patient's medical records.
VULNERABILITY 41: SQL injection vulnerability in patient_id parameter.
VULNERABILITY 42: Exposes sensitive patient information including passwords.</p>
<p>Retrieves all medical records for a specific patient.
Returns sensitive information including patient password, NIK, allergies, and email.</p>

<span id="example-requests-GETapi-medical-records-patient">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/medical-records/patient?patient_id=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/medical-records/patient"
);

const params = {
    "patient_id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-medical-records-patient">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;records&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;patient_id&quot;: 1,
            &quot;doctor_id&quot;: 2,
            &quot;path_file&quot;: &quot;uploads/rekam_medis/abc123.pdf&quot;,
            &quot;disease_name&quot;: &quot;Hypertension&quot;,
            &quot;catatan_dokter&quot;: &quot;Patient shows improvement&quot;,
            &quot;created_at&quot;: &quot;2024-01-15 10:00:00&quot;,
            &quot;patient_name&quot;: &quot;John Doe&quot;,
            &quot;NIK&quot;: &quot;1234567890123456&quot;,
            &quot;allergies&quot;: &quot;Peanuts&quot;,
            &quot;doctor_name&quot;: &quot;Dr. Jane Smith&quot;,
            &quot;patient_email&quot;: &quot;patient@example.com&quot;,
            &quot;password&quot;: &quot;password123&quot;
        }
    ],
    &quot;patient_id&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-medical-records-patient" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-medical-records-patient"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-medical-records-patient"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-medical-records-patient" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-medical-records-patient">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-medical-records-patient" data-method="GET"
      data-path="api/medical-records/patient"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-medical-records-patient', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-medical-records-patient"
                    onclick="tryItOut('GETapi-medical-records-patient');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-medical-records-patient"
                    onclick="cancelTryOut('GETapi-medical-records-patient');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-medical-records-patient"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/medical-records/patient</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-medical-records-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-medical-records-patient"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patient_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patient_id"                data-endpoint="GETapi-medical-records-patient"
               value="1"
               data-component="query">
    <br>
<p>The ID of the patient (vulnerable to SQL injection). Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="medical-records-GETapi-medical-records--id-">Get Medical Record by ID</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Retrieves a specific medical record by its ID.
Requires authentication.</p>

<span id="example-requests-GETapi-medical-records--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/medical-records/architecto?id=1" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/medical-records/architecto"
);

const params = {
    "id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-medical-records--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;record&quot;: {
        &quot;id&quot;: 1,
        &quot;patient_id&quot;: 1,
        &quot;doctor_id&quot;: 2,
        &quot;path_file&quot;: &quot;uploads/rekam_medis/abc123.pdf&quot;,
        &quot;disease_name&quot;: &quot;Hypertension&quot;,
        &quot;catatan_dokter&quot;: &quot;Patient shows improvement&quot;,
        &quot;created_at&quot;: &quot;2024-01-15 10:00:00&quot;,
        &quot;updated_at&quot;: &quot;2024-01-15 10:00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Medical record not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-medical-records--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-medical-records--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-medical-records--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-medical-records--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-medical-records--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-medical-records--id-" data-method="GET"
      data-path="api/medical-records/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-medical-records--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-medical-records--id-"
                    onclick="tryItOut('GETapi-medical-records--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-medical-records--id-"
                    onclick="cancelTryOut('GETapi-medical-records--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-medical-records--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/medical-records/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-medical-records--id-"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-medical-records--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-medical-records--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-medical-records--id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the medical record. Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-medical-records--id-"
               value="1"
               data-component="query">
    <br>
<p>The ID of the medical record. Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="medical-records-POSTapi-medical-records-update">Update Medical Record</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Updates an existing medical record's information.
Requires authentication and validates the medical record exists.</p>

<span id="example-requests-POSTapi-medical-records-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/medical-records/update" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"id\": 1,
    \"doctor_note\": \"Follow-up required\",
    \"disease_name\": \"Diabetes Type 2\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/medical-records/update"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1,
    "doctor_note": "Follow-up required",
    "disease_name": "Diabetes Type 2"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-medical-records-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;record&quot;: {
        &quot;id&quot;: 1,
        &quot;patient_id&quot;: 1,
        &quot;doctor_id&quot;: 2,
        &quot;path_file&quot;: &quot;uploads/rekam_medis/abc123.pdf&quot;,
        &quot;disease_name&quot;: &quot;Diabetes Type 2&quot;,
        &quot;catatan_dokter&quot;: &quot;Follow-up required&quot;,
        &quot;created_at&quot;: &quot;2024-01-15 10:00:00&quot;,
        &quot;updated_at&quot;: &quot;2024-01-15 11:00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Medical record not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The given data was invalid.&quot;,
    &quot;errors&quot;: {
        &quot;id&quot;: [
            &quot;The id field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-medical-records-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-medical-records-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-medical-records-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-medical-records-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-medical-records-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-medical-records-update" data-method="POST"
      data-path="api/medical-records/update"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-medical-records-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-medical-records-update"
                    onclick="tryItOut('POSTapi-medical-records-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-medical-records-update"
                    onclick="cancelTryOut('POSTapi-medical-records-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-medical-records-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/medical-records/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-medical-records-update"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-medical-records-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-medical-records-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-medical-records-update"
               value="1"
               data-component="body">
    <br>
<p>The ID of the medical record to update. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>doctor_note</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="doctor_note"                data-endpoint="POSTapi-medical-records-update"
               value="Follow-up required"
               data-component="body">
    <br>
<p>optional Updated doctor's notes. Example: <code>Follow-up required</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>disease_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="disease_name"                data-endpoint="POSTapi-medical-records-update"
               value="Diabetes Type 2"
               data-component="body">
    <br>
<p>optional Updated disease name. Example: <code>Diabetes Type 2</code></p>
        </div>
        </form>

                    <h2 id="medical-records-DELETEapi-medical-records--id--delete">Delete Medical Record by ID</h2>

<p>
</p>

<p>VULNERABILITY 43: No authorization check - anyone can delete any medical record.</p>
<p>Deletes a medical record and its associated file from storage.
No authorization or ownership verification.</p>

<span id="example-requests-DELETEapi-medical-records--id--delete">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/medical-records/architecto/delete?id=1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/medical-records/architecto/delete"
);

const params = {
    "id": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-medical-records--id--delete">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Medical record deleted&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Medical record not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-medical-records--id--delete" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-medical-records--id--delete"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-medical-records--id--delete"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-medical-records--id--delete" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-medical-records--id--delete">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-medical-records--id--delete" data-method="DELETE"
      data-path="api/medical-records/{id}/delete"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-medical-records--id--delete', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-medical-records--id--delete"
                    onclick="tryItOut('DELETEapi-medical-records--id--delete');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-medical-records--id--delete"
                    onclick="cancelTryOut('DELETEapi-medical-records--id--delete');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-medical-records--id--delete"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/medical-records/{id}/delete</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-medical-records--id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-medical-records--id--delete"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-medical-records--id--delete"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the medical record. Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-medical-records--id--delete"
               value="1"
               data-component="query">
    <br>
<p>The ID of the medical record to delete. Example: <code>1</code></p>
            </div>
                </form>

                <h1 id="patients">Patients</h1>

    

                                <h2 id="patients-GETapi-patients-search">Search Patients by Name</h2>

<p>
</p>

<p>Searches for patients by their full name (partial match supported).</p>

<span id="example-requests-GETapi-patients-search">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/patients/search?name=John" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/search"
);

const params = {
    "name": "John",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-patients-search">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;patients&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;user_id&quot;: 1,
            &quot;NIK&quot;: &quot;1234567890123456&quot;,
            &quot;full_name&quot;: &quot;John Doe&quot;,
            &quot;picture&quot;: &quot;patient.jpg&quot;,
            &quot;allergies&quot;: &quot;Peanuts&quot;,
            &quot;disease_histories&quot;: &quot;Asthma&quot;,
            &quot;email&quot;: &quot;patient@example.com&quot;,
            &quot;phone&quot;: &quot;08123456789&quot;,
            &quot;address&quot;: &quot;123 Main St&quot;
        }
    ],
    &quot;count&quot;: 1
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-patients-search" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-patients-search"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-patients-search"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-patients-search" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-patients-search">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-patients-search" data-method="GET"
      data-path="api/patients/search"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-patients-search', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-patients-search"
                    onclick="tryItOut('GETapi-patients-search');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-patients-search"
                    onclick="cancelTryOut('GETapi-patients-search');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-patients-search"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/patients/search</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-patients-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-patients-search"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-patients-search"
               value="John"
               data-component="query">
    <br>
<p>Part or full name of the patient to search. Example: <code>John</code></p>
            </div>
                </form>

                    <h2 id="patients-GETapi-patients--patientId-">Get Patient by ID</h2>

<p>
</p>

<p>Retrieves patient information by patient ID.</p>

<span id="example-requests-GETapi-patients--patientId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/patients/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-patients--patientId-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;patient&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 1,
        &quot;NIK&quot;: &quot;1234567890123456&quot;,
        &quot;full_name&quot;: &quot;John Doe&quot;,
        &quot;picture&quot;: &quot;patient.jpg&quot;,
        &quot;allergies&quot;: &quot;Peanuts&quot;,
        &quot;disease_histories&quot;: &quot;Asthma&quot;,
        &quot;email&quot;: &quot;patient@example.com&quot;,
        &quot;phone&quot;: &quot;08123456789&quot;,
        &quot;address&quot;: &quot;123 Main St&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;patient@example.com&quot;,
            &quot;role&quot;: &quot;patient&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Patient not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-patients--patientId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-patients--patientId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-patients--patientId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-patients--patientId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-patients--patientId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-patients--patientId-" data-method="GET"
      data-path="api/patients/{patientId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-patients--patientId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-patients--patientId-"
                    onclick="tryItOut('GETapi-patients--patientId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-patients--patientId-"
                    onclick="cancelTryOut('GETapi-patients--patientId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-patients--patientId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/patients/{patientId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-patients--patientId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-patients--patientId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patientId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patientId"                data-endpoint="GETapi-patients--patientId-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the patient. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="patients-GETapi-patients-user--userId-">Get Patient by User ID</h2>

<p>
</p>

<p>Retrieves patient information by user ID.</p>

<span id="example-requests-GETapi-patients-user--userId-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/patients/user/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/user/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-patients-user--userId-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;patient&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 1,
        &quot;NIK&quot;: &quot;1234567890123456&quot;,
        &quot;full_name&quot;: &quot;John Doe&quot;,
        &quot;picture&quot;: &quot;patient.jpg&quot;,
        &quot;allergies&quot;: &quot;Peanuts&quot;,
        &quot;disease_histories&quot;: &quot;Asthma&quot;,
        &quot;email&quot;: &quot;patient@example.com&quot;,
        &quot;phone&quot;: &quot;08123456789&quot;,
        &quot;address&quot;: &quot;123 Main St&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;patient@example.com&quot;,
            &quot;role&quot;: &quot;patient&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Patient not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-patients-user--userId-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-patients-user--userId-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-patients-user--userId-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-patients-user--userId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-patients-user--userId-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-patients-user--userId-" data-method="GET"
      data-path="api/patients/user/{userId}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-patients-user--userId-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-patients-user--userId-"
                    onclick="tryItOut('GETapi-patients-user--userId-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-patients-user--userId-"
                    onclick="cancelTryOut('GETapi-patients-user--userId-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-patients-user--userId-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/patients/user/{userId}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-patients-user--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-patients-user--userId-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>userId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="userId"                data-endpoint="GETapi-patients-user--userId-"
               value="1"
               data-component="url">
    <br>
<p>The user ID of the patient. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="patients-POSTapi-patients--patientId--profile-fill">Update Patient Personal Data</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>VULNERABILITY 33: Mass assignment vulnerability (no input filtering).
VULNERABILITY 34: Missing authorization check in controller layer.</p>
<p>Allows updating patient personal information.
Authorization is checked in service layer using Bearer token.</p>

<span id="example-requests-POSTapi-patients--patientId--profile-fill">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/patients/1/profile/fill" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Smith\",
    \"email\": \"johnsmith@example.com\",
    \"phone\": \"08198765432\",
    \"address\": \"456 Oak Avenue\",
    \"nik\": \"9876543210987654\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/1/profile/fill"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "phone": "08198765432",
    "address": "456 Oak Avenue",
    "nik": "9876543210987654"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-patients--patientId--profile-fill">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Patient not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-patients--patientId--profile-fill" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-patients--patientId--profile-fill"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-patients--patientId--profile-fill"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-patients--patientId--profile-fill" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-patients--patientId--profile-fill">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-patients--patientId--profile-fill" data-method="POST"
      data-path="api/patients/{patientId}/profile/fill"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-patients--patientId--profile-fill', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-patients--patientId--profile-fill"
                    onclick="tryItOut('POSTapi-patients--patientId--profile-fill');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-patients--patientId--profile-fill"
                    onclick="cancelTryOut('POSTapi-patients--patientId--profile-fill');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-patients--patientId--profile-fill"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/patients/{patientId}/profile/fill</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patientId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patientId"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="1"
               data-component="url">
    <br>
<p>The ID of the patient to update. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="John Smith"
               data-component="body">
    <br>
<p>Full name of the patient. Example: <code>John Smith</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="johnsmith@example.com"
               data-component="body">
    <br>
<p>Email address. Example: <code>johnsmith@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="08198765432"
               data-component="body">
    <br>
<p>Phone number. Example: <code>08198765432</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="456 Oak Avenue"
               data-component="body">
    <br>
<p>Home address. Example: <code>456 Oak Avenue</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nik</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nik"                data-endpoint="POSTapi-patients--patientId--profile-fill"
               value="9876543210987654"
               data-component="body">
    <br>
<p>National ID number. Example: <code>9876543210987654</code></p>
        </div>
        </form>

                    <h2 id="patients-GETapi-patients--patientId--statistics">View Patient Statistics</h2>

<p>
</p>

<p>VULNERABILITY 35: Statistics endpoint with SQL injection vulnerability.
VULNERABILITY 36: No rate limiting on data-heavy operations.
VULNERABILITY 37: Exposes aggregated medical data without proper authorization.</p>
<p>Returns patient medical visit statistics and disease history.
Allows filtering by date range.</p>

<span id="example-requests-GETapi-patients--patientId--statistics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/patients/1/statistics?date_from=2024-01-01&amp;date_to=2024-12-31" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/1/statistics"
);

const params = {
    "date_from": "2024-01-01",
    "date_to": "2024-12-31",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-patients--patientId--statistics">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;statistics&quot;: [
        {
            &quot;total_visits&quot;: 5,
            &quot;disease_name&quot;: &quot;Hypertension&quot;
        },
        {
            &quot;total_visits&quot;: 3,
            &quot;disease_name&quot;: &quot;Diabetes&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-patients--patientId--statistics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-patients--patientId--statistics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-patients--patientId--statistics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-patients--patientId--statistics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-patients--patientId--statistics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-patients--patientId--statistics" data-method="GET"
      data-path="api/patients/{patientId}/statistics"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-patients--patientId--statistics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-patients--patientId--statistics"
                    onclick="tryItOut('GETapi-patients--patientId--statistics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-patients--patientId--statistics"
                    onclick="cancelTryOut('GETapi-patients--patientId--statistics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-patients--patientId--statistics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/patients/{patientId}/statistics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-patients--patientId--statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-patients--patientId--statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>patientId</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="patientId"                data-endpoint="GETapi-patients--patientId--statistics"
               value="1"
               data-component="url">
    <br>
<p>The ID of the patient (vulnerable to SQL injection). Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="date_from"                data-endpoint="GETapi-patients--patientId--statistics"
               value="2024-01-01"
               data-component="query">
    <br>
<p>optional Start date for statistics (Y-m-d format). Example: <code>2024-01-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="date_to"                data-endpoint="GETapi-patients--patientId--statistics"
               value="2024-12-31"
               data-component="query">
    <br>
<p>optional End date for statistics (Y-m-d format). Example: <code>2024-12-31</code></p>
            </div>
                </form>

                    <h2 id="patients-GETapi-patients-profile-now">Get Current Patient Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the authenticated patient's profile information.
Requires valid authentication token.</p>

<span id="example-requests-GETapi-patients-profile-now">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/patients/profile/now" \
    --header "Authorization: Bearer {token}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/patients/profile/now"
);

const headers = {
    "Authorization": "Bearer {token}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-patients-profile-now">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;patient&quot;: {
        &quot;id&quot;: 1,
        &quot;user_id&quot;: 1,
        &quot;NIK&quot;: &quot;1234567890123456&quot;,
        &quot;full_name&quot;: &quot;John Doe&quot;,
        &quot;picture&quot;: &quot;patient.jpg&quot;,
        &quot;allergies&quot;: &quot;Peanuts&quot;,
        &quot;disease_histories&quot;: &quot;Asthma&quot;,
        &quot;email&quot;: &quot;patient@example.com&quot;,
        &quot;phone&quot;: &quot;08123456789&quot;,
        &quot;address&quot;: &quot;123 Main St&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;patient@example.com&quot;,
            &quot;role&quot;: &quot;patient&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Token not provided&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Invalid or expired token&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Patient not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-patients-profile-now" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-patients-profile-now"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-patients-profile-now"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-patients-profile-now" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-patients-profile-now">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-patients-profile-now" data-method="GET"
      data-path="api/patients/profile/now"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-patients-profile-now', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-patients-profile-now"
                    onclick="tryItOut('GETapi-patients-profile-now');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-patients-profile-now"
                    onclick="cancelTryOut('GETapi-patients-profile-now');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-patients-profile-now"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/patients/profile/now</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-patients-profile-now"
               value="Bearer {token}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {token}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-patients-profile-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-patients-profile-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
