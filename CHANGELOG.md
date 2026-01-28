# Changelog

All notable changes to the Moodle Coursework API Plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2024-12-14

###  Major Release - Complete Rewrite

This is a complete rewrite of the plugin with significant improvements and new features.

###  Added
- **RESTful API Wrapper**: New JSON-based RESTful endpoints for modern integrations
- **Enhanced Admin Interface**: Comprehensive intake management UI with better UX
- **Improved JavaScript Injection**: Modern AMD module for dynamic form injection
- **Privacy API**: Full GDPR compliance with privacy provider implementation
- **Comprehensive Documentation**: In-plugin API documentation with interactive examples
- **Postman Collection**: Ready-to-use API testing collection
- **Multi-version Support**: Official support for Moodle 4.0 to 5.0+
- **Audit Trail**: Complete tracking of who created/modified intake assignments
- **Active/Inactive Toggle**: Better intake lifecycle management
- **Enhanced Error Handling**: Detailed error messages and validation
- **Code Examples**: cURL, PHP, JavaScript, and Delphi examples included

###  Improved
- **Database Schema**: Optimized with proper foreign keys and constraints
- **API Response Format**: Standardized JSON responses across all endpoints
- **Security**: Enhanced capability checks and context validation
- **Performance**: Optimized database queries and caching
- **Code Quality**: Following Moodle coding standards
- **Language Support**: Professional translations and better string management

###  Fixed
- Token validation issues in RESTful endpoints
- Race conditions in form injection
- Memory leaks in large result sets
- Timezone handling in date fields
- Edge cases in intake code validation

###  Changed
- **Breaking**: API endpoint URLs restructured for RESTful pattern
- **Breaking**: Response format now consistently returns objects, not arrays
- Database table prefixes changed from `local_intake_*` to `local_cwapi_*`
- Minimum Moodle version raised to 4.0
- Minimum PHP version raised to 7.4

###  Removed
- Legacy v1.x API endpoints (deprecated)
- Support for Moodle 3.x versions
- Obsolete database upgrade paths
- Unused language strings

## [1.2.0] - 2023-06-15

### Added
- Basic assignment support
- Simple intake assignment to quizzes

### Fixed
- Database installation errors on PostgreSQL
- Missing capability checks

## [1.1.0] - 2023-03-10

### Added
- Initial web service API
- Basic intake management

## [1.0.0] - 2023-01-15

### Added
- Initial release
- Basic functionality for intake tracking
- Quiz intake assignment

---

## Migration Guide (v1.x to v2.0)

### Database Changes
The plugin will automatically migrate your data during upgrade. However, please:
1. **Backup your database** before upgrading
2. Review the new table structure in `db/install.xml`
3. Update any custom integrations to use new API endpoints

### API Changes
If you'"'"'re using the API:

**Old Format (v1.x):**
```php
/webservice/rest/server.php?wsfunction=local_intake_get_student_work
```

**New Format (v2.0):**
```php
/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake
```

### Response Format Changes
Responses are now consistently structured:

**Old:**
```json
[{"id": 1, "name": "Quiz 1"}]
```

**New:**
```json
{
  "coursework": [
    {
      "id": 1,
      "name": "Quiz 1",
      "type": "quiz",
      "intake": "Janmay2025"
    }
  ]
}
```

---

## Roadmap

### Planned for v2.1.0
- [ ] Bulk intake assignment
- [ ] Import/export intakes via CSV
- [ ] Enhanced reporting dashboard
- [ ] Webhook notifications
- [ ] GraphQL API support

### Planned for v2.2.0
- [ ] Support for custom activity types
- [ ] Integration with Moodle Analytics
- [ ] Advanced filtering options
- [ ] Mobile app API optimizations

### Under Consideration
- Support for intake hierarchies (semesters, terms, etc.)
- Student self-enrollment into intakes
- Automated intake creation based on patterns
- Integration with external student systems

---

## Support

For questions about specific versions:
- Check the [GitHub Releases](https://github.com/mhawere/MoodleCourseApi/releases)
- Review [closed issues](https://github.com/mhawere/MoodleCourseApi/issues?q=is%3Aissue+is%3Aclosed)
- Open a new issue if needed

---

**Note**: Dates follow YYYY-MM-DD format. All times are in UTC.
