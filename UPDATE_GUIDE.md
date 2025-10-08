# Space Conduct Agreement Module - Update Guide

**Author:** D Cube Consulting (info@dcubeconsulting.co.uk)  
**Version:** 1.0.0

## Overview

This guide provides instructions for updating the Space Conduct Agreement module when the HumHub base installation is updated.

## Pre-Update Checklist

### 1. Verify Current Module Status
```bash
# Check current module version
php protected/yii module/info space-conduct-agreement

# Check module status
php protected/yii module/list | grep space-conduct-agreement

# Verify database tables exist
php protected/yii db/query "SHOW TABLES LIKE 'space_conduct_agreement'"
php protected/yii db/query "SHOW TABLES LIKE 'user_conduct_agreement'"
```

### 2. Create Backup
```bash
# Backup module-specific data
mysqldump -u [username] -p [database_name] space_conduct_agreement user_conduct_agreement > space-conduct-agreement-backup-$(date +%Y%m%d-%H%M%S).sql

# Backup module files
cp -r protected/modules/space-conduct-agreement /tmp/space-conduct-agreement-backup-$(date +%Y%m%d-%H%M%S)
```

### 3. Check HumHub Compatibility
```bash
# Check HumHub version
php protected/yii version

# Verify PHP version compatibility
php -v

# Check module.json for minimum HumHub version
cat protected/modules/space-conduct-agreement/module.json | grep minVersion
```

## Update Process

### Step 1: Disable Module (Optional but Recommended)
```bash
# Disable module before HumHub update
php protected/yii module/disable space-conduct-agreement

# Verify module is disabled
php protected/yii module/list | grep space-conduct-agreement
```

### Step 2: Update HumHub Base
Follow the official HumHub update process:
```bash
# Backup entire HumHub installation
cp -r /path/to/humhub /path/to/humhub-backup-$(date +%Y%m%d-%H%M%S)

# Update HumHub files (follow official HumHub update guide)
# This may involve git pull, composer update, etc.

# Run HumHub migrations
php protected/yii migrate/up

# Clear HumHub cache
php protected/yii cache/flush-all
```

### Step 3: Verify Module Compatibility
```bash
# Check if module files are still intact
ls -la protected/modules/space-conduct-agreement/

# Verify module configuration
cat protected/modules/space-conduct-agreement/config.php

# Check for any file permission issues
find protected/modules/space-conduct-agreement/ -type f -exec ls -la {} \;
```

### Step 4: Update Module Files (If Needed)
If the HumHub update includes breaking changes:

```bash
# Download latest module version (if available)
# Replace with actual download/update process
wget https://github.com/dcubeconsulting/space-conduct-agreement/archive/main.zip
unzip main.zip

# Backup current module
cp -r protected/modules/space-conduct-agreement protected/modules/space-conduct-agreement-old

# Update module files
cp -r space-conduct-agreement-main/* protected/modules/space-conduct-agreement/

# Set proper permissions
sudo chown -R www-data:www-data protected/modules/space-conduct-agreement/
sudo find protected/modules/space-conduct-agreement/ -type f -exec chmod 644 {} \;
sudo find protected/modules/space-conduct-agreement/ -type d -exec chmod 755 {} \;
```

### Step 5: Run Module Migrations
```bash
# Check for new migrations
php protected/yii migrate/history --migrationPath=protected/modules/space-conduct-agreement/migrations

# Run any new migrations
php protected/yii migrate/up --migrationPath=protected/modules/space-conduct-agreement/migrations

# Verify migration status
php protected/yii migrate/history --migrationPath=protected/modules/space-conduct-agreement/migrations
```

### Step 6: Re-enable Module
```bash
# Enable the module
php protected/yii module/enable space-conduct-agreement

# Verify module is enabled
php protected/yii module/list | grep space-conduct-agreement
```

### Step 7: Clear Cache
```bash
# Clear all caches
php protected/yii cache/flush-all

# Clear runtime cache
rm -rf protected/runtime/cache/*
rm -rf protected/runtime/HTML/*
```

## Post-Update Verification

### 1. Test Module Functionality
```bash
# Check module status
php protected/yii module/info space-conduct-agreement

# Verify database tables
php protected/yii db/query "DESCRIBE space_conduct_agreement"
php protected/yii db/query "DESCRIBE user_conduct_agreement"

# Check for any errors in logs
tail -f protected/runtime/logs/app.log
```

### 2. Functional Testing
1. **Admin Interface Test**:
   - Go to a space's admin panel
   - Navigate to "Conduct Agreement"
   - Verify you can configure agreement text
   - Test agreement settings

2. **User Interface Test**:
   - Create a test space with conduct agreement
   - Try to join the space with another account
   - Verify agreement prompt appears
   - Test accepting and declining the agreement

3. **Database Test**:
   - Verify agreement acceptance is recorded
   - Check that users can't bypass the agreement
   - Test agreement cleanup when users leave

### 3. Performance Check
```bash
# Check for any performance issues
tail -f protected/runtime/logs/app.log | grep -i "error\|warning\|slow"

# Monitor database queries (if using query logging)
tail -f protected/runtime/logs/db.log
```

## Troubleshooting Update Issues

### Common Update Problems

#### 1. Module Not Loading After Update
```bash
# Check file permissions
ls -la protected/modules/space-conduct-agreement/

# Verify module configuration
cat protected/modules/space-conduct-agreement/config.php

# Check for syntax errors
php -l protected/modules/space-conduct-agreement/Module.php

# Clear cache and try again
php protected/yii cache/flush-all
```

#### 2. Database Migration Errors
```bash
# Check migration status
php protected/yii migrate/history --migrationPath=protected/modules/space-conduct-agreement/migrations

# Check for migration conflicts
php protected/yii migrate/new --migrationPath=protected/modules/space-conduct-agreement/migrations

# If migrations fail, restore from backup
mysql -u [username] -p [database_name] < space-conduct-agreement-backup-[DATE].sql
```

#### 3. Compatibility Issues
```bash
# Check HumHub version compatibility
php protected/yii version
cat protected/modules/space-conduct-agreement/module.json

# Check for deprecated functions
grep -r "deprecated\|DEPRECATED" protected/modules/space-conduct-agreement/

# Review HumHub changelog for breaking changes
```

#### 4. Permission Issues
```bash
# Fix file permissions
sudo chown -R www-data:www-data protected/modules/space-conduct-agreement/
sudo find protected/modules/space-conduct-agreement/ -type f -exec chmod 644 {} \;
sudo find protected/modules/space-conduct-agreement/ -type d -exec chmod 755 {} \;
```

#### 5. Agreement Not Showing
```bash
# Check if agreement is configured for the space
php protected/yii db/query "SELECT * FROM space_conduct_agreement WHERE space_id = [SPACE_ID]"

# Check if user has already accepted
php protected/yii db/query "SELECT * FROM user_conduct_agreement WHERE user_id = [USER_ID] AND space_id = [SPACE_ID]"

# Check event handlers are working
php protected/yii db/query "SELECT * FROM user_conduct_agreement ORDER BY created_at DESC LIMIT 5"
```

### Rollback Plan
If the update causes issues:

```bash
# Disable module
php protected/yii module/disable space-conduct-agreement

# Restore module files from backup
rm -rf protected/modules/space-conduct-agreement/
cp -r /tmp/space-conduct-agreement-backup-[DATE]/ protected/modules/space-conduct-agreement/

# Restore database from backup
mysql -u [username] -p [database_name] < space-conduct-agreement-backup-[DATE].sql

# Re-enable module
php protected/yii module/enable space-conduct-agreement

# Clear cache
php protected/yii cache/flush-all
```

## Version-Specific Update Notes

### HumHub 1.16.x to 1.17.x
- Check for any deprecated Yii framework methods
- Verify event handling compatibility
- Test agreement acceptance workflow
- Review permission system changes

### HumHub 1.15.x to 1.16.x
- Review database schema changes
- Check for permission system updates
- Verify widget compatibility
- Test space membership events

### PHP Version Updates
- PHP 7.4 to 8.0: Check for deprecated functions
- PHP 8.0 to 8.1: Verify type declarations
- PHP 8.1 to 8.2: Check for new deprecations

## Maintenance After Update

### 1. Monitor Logs
```bash
# Set up log monitoring
tail -f protected/runtime/logs/app.log | grep -i "space-conduct-agreement"

# Check for any new errors
grep -i "error\|exception" protected/runtime/logs/app.log | tail -20
```

### 2. Update Documentation
- Update any custom documentation
- Review and update user guides
- Update any custom configurations
- Review compliance requirements

### 3. Performance Optimization
```bash
# Add database indexes if needed
php protected/yii db/query "SHOW INDEX FROM space_conduct_agreement"
php protected/yii db/query "SHOW INDEX FROM user_conduct_agreement"

# Monitor query performance
php protected/yii db/query "EXPLAIN SELECT * FROM user_conduct_agreement WHERE user_id = 1 AND space_id = 1"
```

### 4. Compliance Review
```bash
# Check agreement acceptance data
php protected/yii db/query "SELECT COUNT(*) as total_agreements FROM user_conduct_agreement"

# Review agreement content
php protected/yii db/query "SELECT DISTINCT agreement_text FROM space_conduct_agreement"

# Check for any compliance issues
php protected/yii db/query "SELECT * FROM user_conduct_agreement WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)"
```

## Support

For update-related issues:
- **Email:** info@dcubeconsulting.co.uk
- **Documentation:** Check the main README.md file
- **Logs:** Review protected/runtime/logs/ for detailed error information
- **HumHub Community:** Check HumHub forums for compatibility issues

## Best Practices

### Before Updates
1. Always create full backups
2. Test updates in a staging environment first
3. Review HumHub changelog for breaking changes
4. Check module compatibility matrix
5. Review compliance requirements

### During Updates
1. Follow HumHub's official update process
2. Disable modules before major updates
3. Monitor logs during the update process
4. Test functionality immediately after update
5. Verify agreement data integrity

### After Updates
1. Verify all module features work correctly
2. Monitor performance and error logs
3. Update any custom configurations
4. Document any issues or workarounds
5. Review compliance and legal requirements

## Compliance Considerations

### GDPR Compliance
- Ensure agreement data handling meets GDPR requirements
- Review data retention policies
- Verify user consent mechanisms
- Check data export/deletion capabilities

### Legal Requirements
- Review agreement content for legal enforceability
- Ensure proper consent mechanisms
- Verify audit trail completeness
- Check jurisdiction-specific requirements 