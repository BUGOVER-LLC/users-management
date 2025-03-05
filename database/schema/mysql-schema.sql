/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `Attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Attributes` (
  `attributeId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned NOT NULL,
  `resourceId` int unsigned DEFAULT NULL,
  `clientId` int unsigned DEFAULT NULL,
  `attributeName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributeValue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributeDescription` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`attributeId`),
  KEY `indexAttributessystemId` (`systemId`),
  KEY `indexResourcesresourceId` (`resourceId`),
  KEY `indexAttributesclientId` (`clientId`),
  CONSTRAINT `attributes_clientid_foreign` FOREIGN KEY (`clientId`) REFERENCES `OauthClients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attributes_resourceid_foreign` FOREIGN KEY (`resourceId`) REFERENCES `Resources` (`resourceId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attributes_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `CitizenResetPasswords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `CitizenResetPasswords` (
  `citizenResetPasswordId` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`citizenResetPasswordId`),
  UNIQUE KEY `citizenresetpasswords_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Citizens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Citizens` (
  `citizenId` int unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` int unsigned DEFAULT NULL,
  `profileId` int unsigned DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personType` enum('resident','noneResident','undefined') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentType` enum('NON_BIOMETRIC_PASSPORT','BIRTH_CERTIFICATE','ID_CARD','BIOMETRIC_PASSPORT','RESIDENCE_CARD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentValue` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documentFile` json DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '0',
  `isChecked` tinyint(1) NOT NULL DEFAULT '0',
  `lastActivityAt` datetime DEFAULT NULL,
  `lastDeactivateAt` datetime DEFAULT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`citizenId`),
  UNIQUE KEY `citizens_documentvalue_unique` (`documentValue`),
  KEY `foreign_citizens_profiles_profile_id` (`profileId`),
  KEY `foreign_citizens_users_user_id` (`userId`),
  CONSTRAINT `citizens_profileid_foreign` FOREIGN KEY (`profileId`) REFERENCES `Profiles` (`profileId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `citizens_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `foreign_citizens_profiles_profile_id` FOREIGN KEY (`profileId`) REFERENCES `Profiles` (`profileId`) ON DELETE CASCADE,
  CONSTRAINT `foreign_citizens_users_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ClientDevices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ClientDevices` (
  `clientDeviceId` int unsigned NOT NULL AUTO_INCREMENT,
  `clientId` int unsigned NOT NULL,
  `clientType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loggedAt` datetime DEFAULT NULL,
  `logoutAt` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clientDeviceId`),
  KEY `indexClientDevicesclientId` (`clientId`),
  KEY `indexClientDevicesclientType` (`clientType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ClientUserMapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ClientUserMapping` (
  `clientUserMappingId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned NOT NULL,
  `userId` int unsigned NOT NULL,
  `userType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`clientUserMappingId`),
  KEY `indexClientUserMappingclientId` (`systemId`),
  KEY `indexClientUserMappinguserId` (`userId`),
  KEY `indexClientUserMappinguserType` (`userType`),
  CONSTRAINT `clientusermapping_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Communities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Communities` (
  `communityId` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`communityId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Documents` (
  `documentId` int unsigned NOT NULL AUTO_INCREMENT,
  `citizenId` int unsigned NOT NULL,
  `documentType` enum('NON_BIOMETRIC_PASSPORT','BIRTH_CERTIFICATE','ID_CARD','BIOMETRIC_PASSPORT','RESIDENCE_CARD') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `documentStatus` enum('VALID','INVALID','PRIMARY_VALID') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serialNumber` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `citizenship` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateIssue` date NOT NULL,
  `dateExpiry` date DEFAULT NULL,
  `authority` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` json DEFAULT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`documentId`),
  UNIQUE KEY `documents_serialnumber_unique` (`serialNumber`),
  KEY `documents_citizenid_foreign` (`citizenId`),
  CONSTRAINT `documents_citizenid_foreign` FOREIGN KEY (`citizenId`) REFERENCES `Citizens` (`citizenId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `FailedJobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `FailedJobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failedjobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `InvitationCitizens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `InvitationCitizens` (
  `invitationCitizenId` int unsigned NOT NULL AUTO_INCREMENT,
  `citizenId` int unsigned DEFAULT NULL,
  `inviteUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inviteEmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inviteToken` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passed` datetime NOT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitationCitizenId`),
  UNIQUE KEY `invitationcitizens_inviteurl_unique` (`inviteUrl`),
  UNIQUE KEY `invitationcitizens_inviteemail_unique` (`inviteEmail`),
  UNIQUE KEY `invitationcitizens_invitetoken_unique` (`inviteToken`),
  KEY `foreign_invitation_citizens_citizenId` (`citizenId`),
  CONSTRAINT `foreign_invitation_citizens_citizenId` FOREIGN KEY (`citizenId`) REFERENCES `Citizens` (`citizenId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `InvitationUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `InvitationUsers` (
  `invitationUserId` int unsigned NOT NULL AUTO_INCREMENT,
  `userId` int unsigned NOT NULL,
  `inviteUrl` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `inviteToken` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passed` datetime NOT NULL,
  `psnInfo` json DEFAULT NULL,
  `acceptedAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inviteEmail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`invitationUserId`),
  UNIQUE KEY `invitationusers_inviteurl_unique` (`inviteUrl`),
  UNIQUE KEY `invitationusers_invitetoken_unique` (`inviteToken`),
  UNIQUE KEY `invitationusers_inviteemail_unique` (`inviteEmail`),
  KEY `foreign_invitation_users_user_id` (`userId`),
  CONSTRAINT `foreign_invitation_users_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `OauthAccessTokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OauthAccessTokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int unsigned NOT NULL,
  `deviceId` int unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauthaccesstokens_user_id_index` (`user_id`),
  KEY `indexClientDevicesdeviceId` (`deviceId`),
  CONSTRAINT `oauthaccesstokens_deviceid_foreign` FOREIGN KEY (`deviceId`) REFERENCES `ClientDevices` (`clientDeviceId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `OauthAuthCodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OauthAuthCodes` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  `client_id` int unsigned NOT NULL,
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauthauthcodes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `OauthClients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OauthClients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL COMMENT 'This key reference to Systems',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `oauthclients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `OauthPersonalAccessClients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OauthPersonalAccessClients` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int unsigned NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `OauthRefreshTokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `OauthRefreshTokens` (
  `id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauthrefreshtokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Permissions` (
  `permissionId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned NOT NULL,
  `clientId` int unsigned DEFAULT NULL,
  `permissionName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissionValue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissionDescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissionActive` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`permissionId`),
  KEY `indexPermissionssystemId` (`systemId`),
  KEY `indexPermissionsclientId` (`clientId`),
  KEY `indexpermissiondescription` (`permissionDescription`),
  CONSTRAINT `permissions_clientid_foreign` FOREIGN KEY (`clientId`) REFERENCES `OauthClients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permissions_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Producers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Producers` (
  `producerId` int unsigned NOT NULL AUTO_INCREMENT,
  `currentSystemId` int unsigned DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rememberToken` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verifiedAt` datetime NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`producerId`),
  UNIQUE KEY `producers_email_unique` (`email`),
  UNIQUE KEY `producers_username_unique` (`username`),
  KEY `indexSystemsclientId` (`currentSystemId`),
  CONSTRAINT `producers_currentsystemid_foreign` FOREIGN KEY (`currentSystemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Profiles` (
  `profileId` int unsigned NOT NULL AUTO_INCREMENT,
  `psn` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateBirth` date DEFAULT NULL,
  `gender` enum('M','F') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `currentLoginType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currentLoginId` bigint unsigned DEFAULT NULL,
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`profileId`),
  KEY `profiles_currentlogintype_currentloginid_index` (`currentLoginType`,`currentLoginId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Regions` (
  `regionId` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`regionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Resources` (
  `resourceId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned DEFAULT NULL,
  `resourceName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resourceValue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resourceDescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`resourceId`),
  KEY `indexSystemssystemId` (`systemId`),
  CONSTRAINT `resources_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `RolePermission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `RolePermission` (
  `rolePermissionId` int unsigned NOT NULL AUTO_INCREMENT,
  `roleId` int unsigned NOT NULL,
  `permissionId` int unsigned NOT NULL,
  `access` set('create','read','update','delete','copy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'create',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rolePermissionId`),
  KEY `foreign_role_permission_permissions_role_id` (`roleId`),
  KEY `foreign_role_permission_permissions_permission_id` (`permissionId`),
  CONSTRAINT `foreign_role_permission_permissions_permission_id` FOREIGN KEY (`permissionId`) REFERENCES `Permissions` (`permissionId`),
  CONSTRAINT `foreign_role_permission_roles_role_id` FOREIGN KEY (`roleId`) REFERENCES `Roles` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Roles` (
  `roleId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned NOT NULL,
  `clientId` int unsigned DEFAULT NULL,
  `roleName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleValue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleDescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roleActive` tinyint(1) NOT NULL DEFAULT '1',
  `hasSubordinates` tinyint(1) NOT NULL DEFAULT '0',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`roleId`),
  KEY `indexRolessystemId` (`systemId`),
  KEY `indexRolesclientId` (`clientId`),
  KEY `indexrolesdescription` (`roleDescription`),
  CONSTRAINT `roles_clientid_foreign` FOREIGN KEY (`clientId`) REFERENCES `OauthClients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `roles_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Rooms` (
  `roomId` int unsigned NOT NULL AUTO_INCREMENT,
  `systemId` int unsigned NOT NULL,
  `attributeId` int unsigned NOT NULL,
  `roomName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roomValue` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roomDescription` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`roomId`),
  KEY `indexRoomssystemId` (`systemId`),
  KEY `indexRoomsattributeId` (`attributeId`),
  CONSTRAINT `rooms_attributeid_foreign` FOREIGN KEY (`attributeId`) REFERENCES `Attributes` (`attributeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rooms_systemid_foreign` FOREIGN KEY (`systemId`) REFERENCES `Systems` (`systemId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Systems` (
  `systemId` int unsigned NOT NULL AUTO_INCREMENT,
  `producerId` int unsigned NOT NULL,
  `systemName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `systemDomain` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `systemLogo` json DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`systemId`),
  UNIQUE KEY `systems_systemname_unique` (`systemName`),
  KEY `indexSystemsproducerId` (`producerId`),
  CONSTRAINT `systems_producerid_foreign` FOREIGN KEY (`producerId`) REFERENCES `Producers` (`producerId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `userId` int unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profileId` int unsigned DEFAULT NULL,
  `roleId` int unsigned NOT NULL,
  `attributeId` int unsigned DEFAULT NULL,
  `parentId` int unsigned DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `deletedAt` datetime DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_roleid_foreign` (`roleId`),
  KEY `users_attributeid_foreign` (`attributeId`),
  KEY `users_parentid_foreign` (`parentId`),
  KEY `foreign_profiles_profile_id` (`profileId`),
  CONSTRAINT `foreign_profiles_profile_id` FOREIGN KEY (`profileId`) REFERENCES `Profiles` (`profileId`),
  CONSTRAINT `users_attributeid_foreign` FOREIGN KEY (`attributeId`) REFERENCES `Attributes` (`attributeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_parentid_foreign` FOREIGN KEY (`parentId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_roleid_foreign` FOREIGN KEY (`roleId`) REFERENCES `Roles` (`roleId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main users when has roles, attributes ex. (Court,...)';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` int unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (1,'2024_04_09_120899_create_ClientDevices_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (2,'2024_04_09_120900_create_OauthClients_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (3,'2024_04_09_120901_create_OauthAccessTokens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (4,'2024_04_09_120902_create_OauthAuthCodes_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (5,'2024_04_09_120903_create_OauthPersonalAccessClients_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (6,'2024_04_09_120904_create_OauthRefreshTokens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (7,'2024_04_09_120925_create_Producers_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (8,'2024_04_09_120925_create_Systems_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (9,'2024_04_09_120926_create_Resource_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (10,'2024_04_09_120927_create_Attributes_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (11,'2024_04_09_120927_create_Roles_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (12,'2024_04_09_120928_create_Profiles_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (13,'2024_04_09_120928_create_Users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (14,'2024_04_09_120929_create_Citizens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (15,'2024_04_09_120929_create_Communities_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (16,'2024_04_09_120929_create_FailedJobs_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (17,'2024_04_09_120929_create_InvitationUsers_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (18,'2024_04_09_120929_create_Permissions_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (19,'2024_04_09_120929_create_Regions_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (20,'2024_04_09_120929_create_RolePermission_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (21,'2024_04_09_120929_create_Sessions_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (22,'2024_04_09_120929_create_telescope_entries_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (23,'2024_04_09_120929_create_telescope_entries_tags_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (24,'2024_04_09_120929_create_telescope_monitoring_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (25,'2024_04_09_120932_add_foreign_keys_to_Citizens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (26,'2024_04_09_120932_add_foreign_keys_to_InvitationUsers_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (27,'2024_04_09_120932_add_foreign_keys_to_RolePermission_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (28,'2024_04_09_120932_add_foreign_keys_to_Users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (29,'2024_04_09_120932_add_foreign_keys_to_telescope_entries_tags_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (30,'2024_04_17_115552_create__invitation_citizens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (31,'2024_04_17_174755_add_uuid_column_to_users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (32,'2024_04_18_071851_add_invite_email_column_to_invitation_users',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (33,'2024_04_19_091119_create_citizen_reset_password_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (34,'2024_05_06_122215_create_Documents_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (35,'2024_05_16_105205_add_accept_field_to_InvitationUsers_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (36,'2024_06_17_160346_create_ClientUserMapping_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (37,'2024_06_19_153445_add_foreign_key_to_Systems_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (38,'2024_06_27_155354_add_foreign_type_key_to__attributes_table_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (39,'2024_06_28_172653_add_attribute_id_to__users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_07_02_080731_alter_passwordHash_column_from_Users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_07_02_104639_add_phone_column_to__user_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_07_02_132138_add_current_login_column_to__profiles_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_07_08_152822_add_device_id_field_to_OauthAccessTokens_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (44,'2024_07_09_114405_add_parentId_field_to_Users_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (45,'2024_07_09_142815_add_hasSubordinates_field_to_Roles_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (46,'2024_07_15_140934_alter_avatar_column_to_Profiles_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (47,'2024_07_31_011453_add_updated_at_field_to__attributes_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (48,'2024_07_31_125404_change_accept_field_InvitationUsers_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (49,'2024_08_26_155746_create_Rooms_table',1);
INSERT INTO `Migrations` (`id`, `migration`, `batch`) VALUES (50,'2024_10_10_125346_add_deletedAt_field_to_ClientUserMapping_table',2);
