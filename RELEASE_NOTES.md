# Space Conduct Agreement Module - Release v1.0.0

**Author:** D Cube Consulting (info@dcubeconsulting.co.uk)  
**Release Date:** January 2025  
**Version:** 1.0.0  
**HumHub Compatibility:** 1.15.0+

## Overview

Space Conduct Agreement is a HumHub module that enforces space-specific codes of conduct for users joining spaces. This module ensures compliance with community guidelines by requiring users to accept conduct agreements before becoming space members.

## Features

### Core Functionality
- ✅ **Space-Specific Agreements**: Each space can have its own custom conduct agreement
- ✅ **Mandatory Acceptance**: Users must accept agreements before joining spaces
- ✅ **Acceptance Tracking**: Complete audit trail of agreement acceptances
- ✅ **Automatic Cleanup**: Agreement records are cleaned up when users leave spaces
- ✅ **Admin Interface**: Easy configuration through space admin panel

### Technical Features
- ✅ **Database Integration**: Seamless integration with HumHub's database system
- ✅ **Event Handling**: Proper integration with HumHub's event system
- ✅ **Permission System**: Role-based access control for agreement management
- ✅ **Form Validation**: Built-in validation for agreement acceptance
- ✅ **Responsive Design**: Works on desktop and mobile devices
- ✅ **GDPR Compliance**: Proper data handling and retention policies

## Installation

### Prerequisites
- HumHub installation (version 1.15.0 or higher)
- PHP 7.4 or higher
- MySQL/MariaDB database

### Quick Installation
1. Extract the module files to `protected/modules/space-conduct-agreement/`
2. Run database migrations:
   ```bash
   php protected/yii migrate/up --migrationPath=protected/modules/space-conduct-agreement/migrations
   ```
3. Enable the module:
   ```bash
   php protected/yii module/enable space-conduct-agreement
   ```

### Automated Installation
Use the provided deployment script:
```bash
chmod +x deploy-modules.sh
./deploy-modules.sh -p /path/to/humhub
```

## Usage

### For Space Administrators
1. Go to your space's admin panel
2. Click on "Conduct Agreement" in the admin menu
3. Configure the agreement text for your space
4. Set whether the agreement is required for joining
5. Save the configuration

### For Users
1. When attempting to join a space with a conduct agreement
2. The agreement text will be displayed
3. Users must accept the agreement to proceed
4. Agreement acceptance is recorded and tracked
5. Users cannot bypass the agreement requirement

## Database Schema

### space_conduct_agreement
- `id` - Primary key
- `space_id` - Foreign key to space table
- `agreement_text` - The conduct agreement text
- `is_required` - Whether agreement is mandatory for joining (0/1)
- `created_at` - Creation timestamp
- `created_by` - User who created the agreement
- `updated_at` - Last update timestamp
- `updated_by` - User who last updated the agreement

### user_conduct_agreement
- `id` - Primary key
- `user_id` - Foreign key to user table
- `space_id` - Foreign key to space table
- `agreement_id` - Foreign key to space_conduct_agreement table
- `accepted_at` - When the agreement was accepted
- `created_at` - Record creation timestamp

## Configuration

### Module Configuration
The module can be configured through:
- Space admin panel for per-space settings
- Database configuration for global settings
- Permission system for access control

### Agreement Settings
- **Agreement Text**: Custom text for each space's conduct agreement
- **Required/Optional**: Whether agreement acceptance is mandatory
- **Display Options**: How the agreement is presented to users

## Permissions

- Space administrators can configure conduct agreements
- All users must accept agreements when joining spaces
- Permission: `ManageConductAgreement` - Required for agreement management

## Events

The module hooks into several HumHub events:
- `Membership::EVENT_MEMBER_ADDED` - Records agreement acceptance
- `Membership::EVENT_MEMBER_REMOVED` - Cleans up agreement records
- `Controller::EVENT_BEFORE_ACTION` - Checks for pending agreements
- Space access events for agreement enforcement

## Security

### Data Protection
- All user inputs are validated and sanitized
- Database queries use parameterized statements
- Permission checks are enforced at multiple levels
- CSRF protection is implemented

### Access Control
- Role-based permissions for agreement management
- Space-specific access control
- Audit trail for agreement acceptances

### GDPR Compliance
- Proper data retention policies
- User consent mechanisms
- Data export and deletion capabilities
- Audit trail maintenance

## Performance

### Optimization Features
- Efficient database queries with proper indexing
- Caching support for frequently accessed data
- Minimal impact on page load times
- Optimized agreement checking

### Database Indexes
Recommended indexes for optimal performance:
```sql
CREATE INDEX idx_user_conduct_agreement_user_space ON user_conduct_agreement(user_id, space_id);
CREATE INDEX idx_user_conduct_agreement_created ON user_conduct_agreement(created_at);
CREATE INDEX idx_space_conduct_agreement_space ON space_conduct_agreement(space_id);
```

## Compliance

### Legal Requirements
- **Agreement Enforceability**: Ensures agreements are legally binding
- **Consent Mechanisms**: Proper user consent for agreement acceptance
- **Audit Trails**: Complete records of agreement acceptances
- **Data Retention**: Configurable data retention policies

### GDPR Considerations
- **Data Minimization**: Only necessary data is collected
- **User Rights**: Support for data access and deletion requests
- **Consent Management**: Clear consent mechanisms
- **Data Protection**: Secure handling of agreement data

## Troubleshooting

### Common Issues

#### Agreement Not Showing
- Check if agreement is configured for the space
- Verify user hasn't already accepted the agreement
- Ensure event handlers are working properly
- Check database for agreement records

#### Module Not Loading
- Check if module is enabled: `php protected/yii module/list`
- Verify file permissions: `ls -la protected/modules/space-conduct-agreement/`
- Clear cache: `php protected/yii cache/flush-all`

#### Database Errors
- Run migrations: `php protected/yii migrate/up --migrationPath=protected/modules/space-conduct-agreement/migrations`
- Check database connection and permissions
- Verify table structure

#### Permission Issues
- Verify user has proper permissions
- Check space admin settings
- Ensure agreement is properly configured

## Support

### Documentation
- **README.md**: Basic module information
- **DEPLOYMENT.md**: Detailed deployment instructions
- **UPDATE_GUIDE.md**: Update procedures
- **CHANGELOG.md**: Version history

### Contact Information
- **Email:** info@dcubeconsulting.co.uk
- **Support:** Technical support and bug reports
- **Documentation:** Comprehensive guides and tutorials

### Logs
Check HumHub logs for detailed error information:
- `protected/runtime/logs/app.log`
- `protected/runtime/logs/db.log`

## Maintenance

### Regular Tasks
1. **Monitor Agreement Data**: Check for any compliance issues
2. **Review Agreement Content**: Ensure agreements are up to date
3. **Audit Acceptance Records**: Verify data integrity
4. **Update Documentation**: Keep guides current

### Data Retention
- Configure appropriate data retention policies
- Archive old agreement records as needed
- Maintain audit trails for compliance

## Changelog

### Version 1.0.0 (January 2025)
- **Initial Release**
  - Space-specific conduct agreements
  - User acceptance tracking and validation
  - Integration with space membership workflow
  - Automatic cleanup of agreement records
  - Admin interface for agreement configuration
  - User interface for agreement acceptance
  - Database schema and migrations
  - Permission system integration
  - Event handling and integration
  - GDPR compliance features
  - Responsive design support
  - Comprehensive audit trails

## License

**Proprietary Software - All Rights Reserved**

This module is proprietary software developed by D Cube Consulting. All rights are reserved.

**Copyright (c) 2025 D Cube Consulting. All rights reserved.**

This software is provided for commercial use under license. Unauthorized copying, distribution, modification, public display, or public performance of this software is strictly prohibited.

For licensing information, please contact:
- **Email:** info@dcubeconsulting.co.uk
- **Website:** [Your website URL]

## Commercial Licensing

This module is available for commercial licensing. Please contact D Cube Consulting for:
- Commercial license terms
- Pricing information
- Support packages
- Custom development services

## Credits

**Development:** D Cube Consulting  
**Framework:** HumHub (AGPL-3.0)  
**License:** Proprietary - All Rights Reserved 