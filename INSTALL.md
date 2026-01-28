# Quick Installation Guide

## For Moodle Administrators

### Method 1: Via Moodle Interface (Easiest)

1. **Download the latest release**
   - Go to: https://github.com/mhawere/MoodleCourseApi/releases
   - Download the `.zip` file from the latest release

2. **Install via Moodle**
   - Log in to your Moodle site as admin
   - Navigate to: **Site administration  Plugins  Install plugins**
   - Drag and drop the ZIP file or click "Choose a file"
   - Click **Install plugin from the ZIP file**
   - Follow the installation wizard
   - Click **Upgrade Moodle database now**

3. **Purge caches**
   - Go to: **Site administration  Development  Purge all caches**

### Method 2: Via Command Line

```bash
# Navigate to your Moodle installation
cd /path/to/moodle

# Navigate to local plugins directory
cd local

# Clone the repository
git clone https://github.com/mhawere/MoodleCourseApi.git courseworkapi

# Set proper permissions
chmod -R 755 courseworkapi
chown -R www-data:www-data courseworkapi  # Adjust user:group as needed

# Visit your Moodle site
# Navigate to: Site administration  Notifications
# Complete the installation
```

### Method 3: Manual Upload

1. Download and extract the ZIP file
2. Rename the folder to `courseworkapi`
3. Upload to `/path/to/moodle/local/courseworkapi`
4. Visit **Site administration  Notifications** in your Moodle site
5. Complete the installation

## Post-Installation Configuration

### 1. Enable Web Services (Required for API)

```
Site administration  Advanced features
 Check "Enable web services"
 Save changes
```

### 2. Create API Token

```
Site administration  Server  Web services  Manage tokens
 Click "Create token"
 Select a user (usually an admin or service account)
 Select service: "courseworkapi_service"
 Save changes
 Copy the generated token (you'"'"'ll need this for API calls)
```

### 3. Configure Intakes

```
Site administration  Local plugins  Global Intake Management
 Click "Add New Intake"
 Enter intake details:
   - Name: e.g., "January/May 2025"
   - Code: e.g., "Janmay2025"
   - Is Active: Yes
 Save
```

## Quick Test

### Test 1: Check Installation
- Navigate to: **Site administration  Plugins  Plugins overview**
- Search for "courseworkapi"
- Should show: **Local: Coursework API** with version 2.0.0

### Test 2: Test Form Injection
- Create or edit a quiz or assignment
- Look for the **"Intake Assignment"** section
- Should see a dropdown with available intakes

### Test 3: Test API (Optional)

```bash
# Replace with your details
TOKEN="your_token_here"
MOODLE_URL="https://your-moodle-site.com"
STUDENT_ID=123
INTAKE_CODE="Janmay2025"

# Test API call
curl -X POST "${MOODLE_URL}/local/courseworkapi/restful.php/local_courseworkapi_get_all_intakes" \
  -H "Authorization: ${TOKEN}" \
  -H "Content-Type: application/json" \
  -d '"'"'{"activeonly": 1}'"'"'
```

## Troubleshooting

### Issue: Plugin not showing in Moodle
- **Solution**: Check folder name is exactly `courseworkapi` in `/local/` directory

### Issue: Database errors during installation
- **Solution**: Ensure your database user has CREATE TABLE privileges

### Issue: Intake dropdown not appearing
- **Solution**: 
  1. Purge all caches
  2. Check browser console for JavaScript errors
  3. Verify you'"'"'re on a quiz/assignment edit form

### Issue: API returns "Invalid token"
- **Solution**:
  1. Check token hasn'"'"'t expired
  2. Verify web services are enabled
  3. Ensure user has `local/courseworkapi:use_webservice` capability

## System Requirements

- **Moodle**: 4.0 or higher
- **PHP**: 7.4 or higher
- **Database**: MySQL 5.7+ or PostgreSQL 10+
- **Web Server**: Apache 2.4+ or Nginx 1.18+

## Getting Help

- **Documentation**: https://github.com/mhawere/MoodleCourseApi
- **Issues**: https://github.com/mhawere/MoodleCourseApi/issues
- **API Docs**: Available in your Moodle at `/local/courseworkapi/documentation.php`

## Uninstallation

If you need to uninstall:

```
Site administration  Plugins  Plugins overview
 Search for "courseworkapi"
 Click "Uninstall"
 Confirm uninstallation
```

**Note**: This will remove all intake data from the database. Export any data you need before uninstalling.

---

**Need more help?** Check the full [README](README.md) or open an [issue on GitHub](https://github.com/mhawere/MoodleCourseApi/issues).
