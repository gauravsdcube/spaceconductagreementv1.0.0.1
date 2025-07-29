# Space Conduct Agreement Module - Production Deployment Guide

**Author:** D Cube Consulting (info@dcubeconsulting.co.uk)  
**Version:** 1.0.0

## Prerequisites

- HumHub installation (version 1.15.0 or higher)
- Server with PHP 7.4+ and MySQL/MariaDB
- SSH access to the server
- Backup of your existing HumHub installation

## Pre-Deployment Checklist

### 1. Backup Your Installation
```bash
# Create a full backup of your HumHub installation
cp -r /path/to/humhub /path/to/humhub-backup-$(date +%Y%m%d-%H%M%S)

# Backup your database
mysqldump -u [username] -p [database_name] > humhub-backup-$(date +%Y%m%d-%H%M%S).sql
```

### 2. Verify System Requirements
```bash
# Check PHP version
php -v

# Check available PHP extensions
php -m | grep -E "(pdo|mysql|mbstring|intl|gd|zip)"
```

## Deployment Steps

### Step 1: Upload Module Files
```bash
# Navigate to your HumHub installation
cd /path/to/humhub

# Create module directory (if it doesn't exist)
mkdir -p protected/modules/space-conduct-agreement

# Upload module files to the directory
# You can use SCP, SFTP, or your preferred method
scp -r space-conduct-agreement/* user@server:/path/to/humhub/protected/modules/space-conduct-agreement/
```

### Step 2: Set Proper Permissions
```bash
# Set correct ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data protected/modules/space-conduct-agreement/

# Set proper file permissions
sudo find protected/modules/space-conduct-agreement/ -type f -exec chmod 644 {} \;
sudo find protected/modules/space-conduct-agreement/ -type d -exec chmod 755 {} \;

# Make sure the web server can read the files
sudo chmod -R 755 protected/modules/space-conduct-agreement/
```

### Step 3: Run Database Migrations
```bash
# Navigate to HumHub directory
cd /path/to/humhub

# Run the module's database migrations
php protected/yii migrate/up --migrationPath=protected/modules/space-conduct-agreement/migrations

# Verify migration success
php protected/yii migrate/history --migrationPath=protected/modules/space-conduct-agreement/migrations
```

### Step 4: Enable the Module
```bash
# Enable the module via command line
php protected/yii module/enable space-conduct-agreement

# Or enable via web interface:
# 1. Go to Admin Panel > Modules
# 2. Find "Space Conduct Agreement" in the list
# 3. Click "Enable"
```

### Step 5: Clear Cache
```bash
# Clear HumHub cache
php protected/yii cache/flush-all

# Clear runtime cache
rm -rf protected/runtime/cache/*
rm -rf protected/runtime/HTML/*
```

### Step 6: Verify Installation
```bash
# Check if module is properly installed
php protected/yii module/list | grep space-conduct-agreement

# Check module status
php protected/yii module/info space-conduct-agreement
```

## Post-Deployment Configuration

### 1. Configure Space Permissions
- Go to Admin Panel > Users > Permissions
- Ensure space administrators have the "Manage Conduct Agreement" permission

### 2. Configure Conduct Agreements
1. Go to any space's admin panel
2. Look for "Conduct Agreement" in the admin menu
3. Configure the agreement text for the space
4. Set whether the agreement is required for joining

### 3. Test Module Functionality
1. Create a test space
2. Configure a conduct agreement for the space
3. Try to join the space with another user account
4. Verify the agreement prompt appears
5. Test accepting and declining the agreement

### 4. Monitor Logs
```bash
# Monitor HumHub logs for any errors
tail -f protected/runtime/logs/app.log

# Monitor web server logs
tail -f /var/log/apache2/error.log  # For Apache
tail -f /var/log/nginx/error.log    # For Nginx
```

## Troubleshooting

### Common Issues

#### 1. Module Not Appearing in Admin Panel
```bash
# Check if module files are in correct location
ls -la protected/modules/space-conduct-agreement/

# Verify module configuration
cat protected/modules/space-conduct-agreement/config.php

# Clear cache and try again
php protected/yii cache/flush-all
```

#### 2. Database Migration Errors
```bash
# Check migration status
php protected/yii migrate/history --migrationPath=protected/modules/space-conduct-agreement/migrations

# If migration failed, check database connection
php protected/yii db/check

# Rollback and retry if necessary
php protected/yii migrate/down --migrationPath=protected/modules/space-conduct-agreement/migrations
php protected/yii migrate/up --migrationPath=protected/modules/space-conduct-agreement/migrations
```

#### 3. Permission Issues
```bash
# Fix file permissions
sudo chown -R www-data:www-data protected/modules/space-conduct-agreement/
sudo chmod -R 755 protected/modules/space-conduct-agreement/
```

#### 4. Agreement Not Showing
```bash
# Check if agreement is configured for the space
php protected/yii db/query "SELECT * FROM space_conduct_agreement WHERE space_id = [SPACE_ID]"

# Check if user has already accepted
php protected/yii db/query "SELECT * FROM user_conduct_agreement WHERE user_id = [USER_ID] AND space_id = [SPACE_ID]"
```

#### 5. Module Conflicts
```bash
# Check for conflicting modules
php protected/yii module/list

# Disable conflicting modules temporarily
php protected/yii module/disable [conflicting-module-id]
```

## Security Considerations

### 1. File Permissions
- Ensure module files are not writable by web server
- Set proper ownership to web server user
- Restrict access to sensitive files

### 2. Database Security
- Use dedicated database user with minimal privileges
- Regularly backup agreement data
- Monitor for SQL injection attempts

### 3. Agreement Content
- Review agreement text for legal compliance
- Ensure agreements are clear and enforceable
- Consider having legal review of agreement content

### 4. Data Privacy
- Ensure agreement acceptance data is handled according to privacy laws
- Implement data retention policies
- Provide users with access to their agreement history

## Maintenance

### Regular Tasks
1. **Backup Agreement Data**: Regularly backup the `space_conduct_agreement` and `user_conduct_agreement` tables
2. **Monitor Logs**: Check for errors or unusual activity
3. **Update Module**: Keep the module updated with latest security patches
4. **Review Agreements**: Periodically review and update agreement content
5. **Audit Compliance**: Ensure agreement acceptance tracking meets compliance requirements

### Backup Script Example
```bash
#!/bin/bash
# Backup script for Space Conduct Agreement data

DATE=$(date +%Y%m%d-%H%M%S)
DB_NAME="your_database_name"
BACKUP_DIR="/path/to/backups"

# Backup agreement tables
mysqldump -u [username] -p[password] $DB_NAME space_conduct_agreement user_conduct_agreement > $BACKUP_DIR/conduct-agreement-$DATE.sql

# Keep only last 30 days of backups
find $BACKUP_DIR -name "conduct-agreement-*.sql" -mtime +30 -delete
```

### Data Retention Policy
```sql
-- Example: Clean up old agreement records (older than 7 years)
DELETE FROM user_conduct_agreement 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 YEAR);

-- Example: Archive old agreements
INSERT INTO user_conduct_agreement_archive 
SELECT * FROM user_conduct_agreement 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 5 YEAR);
```

## Performance Optimization

### 1. Database Indexing
```sql
-- Add indexes for better performance
CREATE INDEX idx_user_conduct_agreement_user_space ON user_conduct_agreement(user_id, space_id);
CREATE INDEX idx_user_conduct_agreement_created ON user_conduct_agreement(created_at);
```

### 2. Caching
- The module uses HumHub's caching system
- Monitor cache hit rates
- Adjust cache settings as needed

## Support

For technical support or issues:
- **Email:** info@dcubeconsulting.co.uk
- **Documentation:** Check the README.md file
- **Logs:** Review protected/runtime/logs/ for detailed error information

## Rollback Plan

If you need to rollback the module:

```bash
# Disable the module
php protected/yii module/disable space-conduct-agreement

# Rollback database migrations
php protected/yii migrate/down --migrationPath=protected/modules/space-conduct-agreement/migrations

# Remove module files
rm -rf protected/modules/space-conduct-agreement/

# Clear cache
php protected/yii cache/flush-all
```

## Compliance Notes

### GDPR Considerations
- Agreement acceptance data may be considered personal data
- Implement data subject rights (access, deletion, portability)
- Document legal basis for processing agreement data
- Consider data retention periods

### Legal Requirements
- Ensure agreement content is legally enforceable
- Consider jurisdiction-specific requirements
- Implement proper consent mechanisms
- Maintain audit trails for compliance

## Version History

- **1.0.0** - Initial production release
  - Space-specific conduct agreements
  - User acceptance tracking
  - Integration with space membership workflow
  - Automatic cleanup on member removal 