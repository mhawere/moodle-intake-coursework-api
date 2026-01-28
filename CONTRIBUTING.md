# Contributing to Moodle Coursework API Plugin

Thank you for your interest in contributing to the Moodle Coursework API Plugin! This document provides guidelines and instructions for contributing.

##  Ways to Contribute

-  Report bugs and issues
-  Suggest new features or enhancements
-  Improve documentation
-  Submit bug fixes
-  Add new features
-  Write tests
-  Add translations

##  Getting Started

### Prerequisites

- Moodle 4.0+ development environment
- PHP 7.4+
- Git
- Basic understanding of Moodle plugin development

### Setting Up Development Environment

1. **Fork the repository**
   ```bash
   # Fork on GitHub, then clone your fork
   git clone https://github.com/YOUR_USERNAME/MoodleCourseApi.git
   cd MoodleCourseApi
   ```

2. **Add upstream remote**
   ```bash
   git remote add upstream https://github.com/mhawere/moodle-intake-coursework-api.git
   ```

3. **Install in Moodle**
   ```bash
   # Copy/symlink to your Moodle installation
   ln -s /path/to/MoodleCourseApi /path/to/moodle/local/courseworkapi
   ```

4. **Install the plugin in Moodle**
   - Visit Site administration  Notifications
   - Complete the installation

##  Development Guidelines

### Code Standards

- Follow [Moodle Coding Style](https://docs.moodle.org/dev/Coding_style)
- Use proper PHPDoc comments for all functions and classes
- Keep functions focused and modular
- Write self-documenting code with meaningful variable names

### Commit Guidelines

Use semantic commit messages:

```
feat: Add support for assignment groups
fix: Resolve token validation issue
docs: Update API documentation
style: Format code according to standards
refactor: Simplify intake management logic
test: Add unit tests for external API
chore: Update dependencies
```

### Branch Naming

- `feature/description` - For new features
- `fix/description` - For bug fixes
- `docs/description` - For documentation changes
- `refactor/description` - For code refactoring

### Example:
```bash
git checkout -b feature/add-grade-filtering
git checkout -b fix/token-expiration-bug
```

##  Contribution Workflow

1. **Create an issue first** (for significant changes)
   - Describe the problem or feature
   - Discuss the proposed solution
   - Wait for maintainer feedback

2. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Write clean, well-documented code
   - Follow coding standards
   - Add/update tests if applicable

4. **Test thoroughly**
   - Test in a clean Moodle installation
   - Test with different PHP/Moodle versions if possible
   - Ensure no existing functionality is broken

5. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: Add your feature description"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**
   - Provide a clear description of changes
   - Reference any related issues
   - Include screenshots if UI changes are involved

##  Testing

### Manual Testing Checklist

- [ ] Plugin installs without errors
- [ ] All database tables are created correctly
- [ ] Web service endpoints respond correctly
- [ ] JavaScript form injection works on quiz/assignment pages
- [ ] Admin interface is functional
- [ ] No PHP warnings or errors in logs
- [ ] Privacy API implementation works
- [ ] Capabilities are properly checked

### Testing Different Scenarios

- Test with Moodle 4.0, 4.1, 4.2, 4.3, 4.4, 5.0
- Test with MySQL and PostgreSQL
- Test with different user roles (admin, teacher, student)
- Test API with various authentication methods

##  Documentation

When adding features or making changes:

- Update README.md if needed
- Update inline code documentation
- Update API documentation in documentation.php
- Add examples for new API endpoints
- Update CHANGELOG.md

##  Reporting Bugs

When reporting bugs, include:

1. **Moodle version**
2. **PHP version**
3. **Plugin version**
4. **Steps to reproduce**
5. **Expected behavior**
6. **Actual behavior**
7. **Screenshots or error logs** (if applicable)

### Example Bug Report

```markdown
**Moodle Version**: 4.3
**PHP Version**: 8.1
**Plugin Version**: 2.0.0

**Steps to Reproduce**:
1. Navigate to quiz settings
2. Select an intake
3. Save the quiz

**Expected**: Quiz saves with intake assigned
**Actual**: Error "Invalid intake code"

**Error Log**:
[error message here]
```

##  Feature Requests

When requesting features:

1. Check if the feature already exists
2. Explain the use case
3. Describe the expected behavior
4. Provide examples if possible

##  Translation

To add translations:

1. Copy `lang/en/local_courseworkapi.php`
2. Create `lang/[your_language]/local_courseworkapi.php`
3. Translate all strings
4. Test in Moodle with your language
5. Submit a pull request

##  Code of Conduct

- Be respectful and inclusive
- Welcome newcomers
- Focus on constructive criticism
- Help others learn and grow

##  Questions?

- Open an issue for questions
- Tag with "question" label
- Check existing issues first

##  Recognition

Contributors will be acknowledged in:
- CHANGELOG.md
- GitHub contributors page
- Release notes

Thank you for contributing to make this plugin better for the Moodle community!

---

**Happy Coding! **
