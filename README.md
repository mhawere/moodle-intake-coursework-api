#  Moodle Coursework API Plugin

[![Moodle](https://img.shields.io/badge/Moodle-4.0%20to%205.0-orange.svg)](https://moodle.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](LICENSE)
[![Version](https://img.shields.io/badge/Version-2.0.0-green.svg)](https://github.com/mhawere/moodle-intake-coursework-api/releases)
[![Stable](https://img.shields.io/badge/Maturity-Stable-brightgreen.svg)](https://github.com/mhawere/moodle-intake-coursework-api)

> A comprehensive Moodle local plugin that enables lecturers to assign global intake periods to quizzes and assignments, with a robust API for retrieving student coursework data by intake.

---

##  Features

###  Core Functionality
- ** Global Intake Management**: Admin interface to create and manage institution-wide intake periods
- ** Dynamic Form Injection**: JavaScript automatically adds intake selection dropdown to quiz/assignment forms
- ** Comprehensive API**: Both RESTful and standard Moodle web service endpoints
- ** Audit Trail**: Complete tracking of intake assignments with user and timestamp information
- ** Privacy Compliant**: Includes required privacy provider for public plugin distribution

###  Technical Highlights
-  **Moodle 4.0 to 5.0+ Compatible**: Supports current and future Moodle versions
-  **AMD JavaScript Module**: Modern, efficient form injection with proper dependency management
-  **Security First**: Proper capability checks and context validation
-  **Multi-language Support**: Complete language pack with professional translations
-  **RESTful API**: JSON-based endpoints for modern integrations
-  **Production Ready**: Comprehensive testing and documentation

---

##  Installation

### Quick Install (Recommended)
1. Download the latest release from [GitHub Releases](https://github.com/mhawere/moodle-intake-coursework-api/releases)
2. Go to **Site administration  Plugins  Install plugins**
3. Upload the ZIP file and follow the installation wizard
4. Purge all caches after installation

### Manual Install
```bash
cd /path/to/moodle
git clone https://github.com/mhawere/moodle-intake-coursework-api.git local/courseworkapi
```

Then:
1. Visit **Site administration  Notifications** to complete the installation
2. Purge all caches

### Via Git (for developers)
```bash
cd /path/to/moodle/local
git clone https://github.com/mhawere/moodle-intake-coursework-api.git courseworkapi
cd courseworkapi
```

---

##  Post-Installation Setup

### 1. Enable Web Services
- Navigate to **Site administration  Advanced features**
- Enable **Enable web services**

### 2. Create API Token
- Go to **Site administration  Server  Web services  Manage tokens**
- Create a new token for the `courseworkapi_service`

### 3. Set Up Intakes
- Go to **Site administration  Local plugins  Global Intake Management**
- Add your intake periods (e.g., "Jan/May2025", "Sep2024", "Semester1-2025")

---

##  Usage Guide

### For Administrators

#### Managing Intakes
1. Navigate to **Site administration  Local plugins  Global Intake Management**
2. Add new intakes with descriptive names
3. Toggle active/inactive status as needed
4. View comprehensive API documentation

#### API Documentation
- Access full documentation at `/local/courseworkapi/documentation.php`
- Includes Postman collection for easy testing
- Code examples in cURL, PHP, JavaScript, and Delphi

### For Lecturers

#### Assigning Coursework to Intakes
1. Create or edit a quiz or assignment
2. Look for the **"Intake Assignment"** section in the form
3. Select an intake from the dropdown
4. Save the coursework

>  **Note**: The intake dropdown appears automatically - no additional setup required!

### For Developers

#### API Endpoints

**Standard Moodle Web Service:**
```
POST /webservice/rest/server.php
```

**RESTful JSON Endpoint:**
```
POST /local/courseworkapi/restful.php/{function_name}
```

#### Available Functions

<details>
<summary><b>1. Get Student Coursework by Intake</b></summary>

```json
{
  "studentid": 4540,
  "intakecode": "Janmay2025",
  "includeactive": 0
}
```
</details>

<details>
<summary><b>2. Get All Intakes</b></summary>

```json
{
  "activeonly": 1
}
```
</details>

<details>
<summary><b>3. Get Current Intake for Course Module</b></summary>

```json
{
  "cmid": 123
}
```
</details>

---

##  Database Schema

### Tables Created
- `local_cwapi_intakes`: Global intake periods
- `local_cwapi_quiz_map`: Quiz-to-intake mappings
- `local_cwapi_assign_map`: Assignment-to-intake mappings

### Key Features
-  Proper foreign key relationships
-  Audit fields for creation/modification tracking
-  Unique constraints to prevent duplicate mappings

---

##  API Examples

### cURL Example
```bash
curl -X POST "https://yourmoodle.com/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake" \
  -H "Authorization: YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '"'"'{"studentid": 4540, "intakecode": "Janmay2025", "includeactive": 0}'"'"'
```

### PHP Example
```php
$token = '"'"'YOUR_TOKEN'"'"';
$url = '"'"'https://yourmoodle.com/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake'"'"';

$data = [
    '"'"'studentid'"'"' => 4540,
    '"'"'intakecode'"'"' => '"'"'Janmay2025'"'"',
    '"'"'includeactive'"'"' => 0
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    '"'"'Authorization: '"'"' . $token,
    '"'"'Content-Type: application/json'"'"'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$result = json_decode($response, true);
```

### JavaScript/Fetch Example
```javascript
const token = '"'"'YOUR_TOKEN'"'"';
const url = '"'"'https://yourmoodle.com/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake'"'"';

const data = {
    studentid: 4540,
    intakecode: '"'"'Janmay2025'"'"',
    includeactive: 0
};

fetch(url, {
    method: '"'"'POST'"'"',
    headers: {
        '"'"'Authorization'"'"': token,
        '"'"'Content-Type'"'"': '"'"'application/json'"'"'
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(result => console.log(result));
```

---

##  File Structure

```
courseworkapi/
 amd/
    src/intake_form_injection.js      # Source AMD module
    build/intake_form_injection.min.js # Minified version
 classes/privacy/provider.php           # Privacy compliance
 db/
    access.php                         # Capabilities
    install.xml                        # Database schema
    services.php                       # Web service definitions
    upgrade.php                        # Database upgrades
 lang/en/local_courseworkapi.php        # Language strings
 templates/
    intake_management.mustache         # Admin interface
    api_documentation.mustache         # API docs
 externallib.php                        # External API functions
 lib.php                                # Core hooks and functions
 manage.php                             # Admin management page
 documentation.php                      # API documentation page
 restful.php                            # RESTful API wrapper
 styles.css                             # Plugin styling
 version.php                            # Plugin metadata
 LICENSE                                # GPL v3 License
 README.md                              # This file
```

---

##  Capabilities

- `local/courseworkapi:manage_intakes`: Manage global intakes
- `local/courseworkapi:view_intakes`: View intakes
- `local/courseworkapi:use_webservice`: Use API endpoints

---

##  Requirements

| Component | Version |
|-----------|---------|
| Moodle | 4.0 or higher |
| PHP | 7.4 or higher |
| Database | MySQL 5.7+ or PostgreSQL 10+ |
| Web Services | Must be enabled |

---

##  Troubleshooting

<details>
<summary><b>Intake Dropdown Not Appearing</b></summary>

1. Check that JavaScript is enabled in your browser
2. Verify you'"'"'re on a quiz or assignment edit page
3. Check browser console for JavaScript errors
4. Ensure plugin is properly installed and caches are purged
</details>

<details>
<summary><b>API Authentication Issues</b></summary>

1. Verify your token is valid and not expired
2. Check that web services are enabled
3. Ensure the user has appropriate capabilities
4. Verify the service includes the required functions
</details>

<details>
<summary><b>Database Issues</b></summary>

1. Ensure database tables were created during installation
2. Check foreign key relationships are intact
3. Verify user has appropriate database permissions
</details>

---

##  Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to get started.

---

##  License

This plugin is released under the [GPL v3 license](LICENSE), the same as Moodle.

---

##  Version History

| Version | Date | Changes |
|---------|------|---------|
| v2.0.0 | 2024 | Complete rewrite with RESTful API, improved JavaScript injection, and comprehensive admin interface |
| v1.x | - | Legacy versions with basic functionality |

See [CHANGELOG.md](CHANGELOG.md) for detailed version history.

---

##  Support

For issues, feature requests, or contributions:
-  [Report a bug](https://github.com/mhawere/moodle-intake-coursework-api/issues)
-  [Request a feature](https://github.com/mhawere/moodle-intake-coursework-api/issues)
-  [View documentation](https://github.com/mhawere/moodle-intake-coursework-api/wiki)

---

##  Show Your Support

If this plugin helps you, please give it a  on GitHub!

---

<div align="center">

**Developed with  for the Moodle community**

[![GitHub stars](https://img.shields.io/github/stars/mhawere/moodle-intake-coursework-api?style=social)](https://github.com/mhawere/moodle-intake-coursework-api)
[![GitHub forks](https://img.shields.io/github/forks/mhawere/moodle-intake-coursework-api?style=social)](https://github.com/mhawere/moodle-intake-coursework-api/fork)

</div>
