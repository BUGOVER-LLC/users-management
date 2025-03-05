-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 09 2024 г., 08:11
-- Версия сервера: 8.0.36-0ubuntu0.22.04.1
-- Версия PHP: 8.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `auth`
--

-- --------------------------------------------------------

--
-- Структура таблицы `AppealAnswers`
--

CREATE TABLE `AppealAnswers` (
  `courtCaseAppealAnswerId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `courtCaseParticipantId` int UNSIGNED DEFAULT NULL,
  `causerId` int UNSIGNED DEFAULT NULL,
  `causerType` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `attachments` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `AppealCourtCaseDetails`
--

CREATE TABLE `AppealCourtCaseDetails` (
  `appealCourtCaseDetailId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Attributes`
--

CREATE TABLE `Attributes` (
  `attributeId` int UNSIGNED NOT NULL,
  `typeId` int UNSIGNED DEFAULT NULL,
  `attributeName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributeDescription` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `AttributeUser`
--

CREATE TABLE `AttributeUser` (
  `attributeId` int UNSIGNED NOT NULL,
  `userId` int UNSIGNED NOT NULL,
  `parentId` int UNSIGNED DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CassationCourtCaseDetails`
--

CREATE TABLE `CassationCourtCaseDetails` (
  `cassationCourtCaseDetailId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Citizens`
--

CREATE TABLE `Citizens` (
  `citizenId` int UNSIGNED NOT NULL,
  `userId` int UNSIGNED DEFAULT NULL,
  `profileId` int UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isActive` tinyint(1) NOT NULL DEFAULT '0',
  `lastActivityAt` datetime DEFAULT NULL,
  `lastDeactivateAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Clients`
--

CREATE TABLE `Clients` (
  `clientId` int UNSIGNED NOT NULL,
  `clientName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientSecret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirectUri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Clients';

-- --------------------------------------------------------

--
-- Структура таблицы `ClientUserMapping`
--

CREATE TABLE `ClientUserMapping` (
  `mappingId` int UNSIGNED NOT NULL,
  `clientId` int UNSIGNED NOT NULL,
  `userId` int UNSIGNED NOT NULL,
  `accessLevel` enum('read','write','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Companies`
--

CREATE TABLE `Companies` (
  `companyId` int UNSIGNED NOT NULL,
  `executiveId` int UNSIGNED DEFAULT NULL,
  `taxId` char(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regNumber` char(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseAnswers`
--

CREATE TABLE `CourtCaseAnswers` (
  `courtCaseAnswerId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `draftInfo` json DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseAttachedDetached`
--

CREATE TABLE `CourtCaseAttachedDetached` (
  `fromId` int UNSIGNED DEFAULT NULL,
  `toId` int UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseDecisions`
--

CREATE TABLE `CourtCaseDecisions` (
  `courtCaseDecisionId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `type` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'this types for related description used',
  `introduction` text COLLATE utf8mb4_unicode_ci,
  `foundOut` mediumtext COLLATE utf8mb4_unicode_ci,
  `decided` text COLLATE utf8mb4_unicode_ci,
  `attachments` json DEFAULT NULL,
  `status` enum('draft','shouldBePublished','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseDecisionTypes`
--

CREATE TABLE `CourtCaseDecisionTypes` (
  `courtCaseDecisionTypeId` int UNSIGNED NOT NULL,
  `courtTypeId` int UNSIGNED DEFAULT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseOfficeActs`
--

CREATE TABLE `CourtCaseOfficeActs` (
  `courtCaseOfficeActId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `attachments` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseParticipants`
--

CREATE TABLE `CourtCaseParticipants` (
  `courtCaseParticipantId` int UNSIGNED NOT NULL,
  `cortCaseParticipantTypeId` int UNSIGNED DEFAULT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `citizenId` int UNSIGNED DEFAULT NULL,
  `userId` int UNSIGNED DEFAULT NULL,
  `companyId` int UNSIGNED DEFAULT NULL,
  `joinedAt` datetime DEFAULT NULL,
  `leavedAt` datetime DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseParticipantTypes`
--

CREATE TABLE `CourtCaseParticipantTypes` (
  `courtCaseParticipantTypeId` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseRepresentatives`
--

CREATE TABLE `CourtCaseRepresentatives` (
  `courtCaseRepresentativeId` int UNSIGNED NOT NULL,
  `courtCaseParticipantId` int UNSIGNED DEFAULT NULL,
  `citizenId` int UNSIGNED DEFAULT NULL,
  `joinedAt` datetime DEFAULT NULL,
  `leavedAt` datetime DEFAULT NULL,
  `attachmentPath` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCases`
--

CREATE TABLE `CourtCases` (
  `courtCaseId` int UNSIGNED NOT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `code` char(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courtTypeid` int UNSIGNED DEFAULT NULL,
  `officeResponseStatus` enum('pending','accepted','acted') COLLATE utf8mb4_unicode_ci DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseSessionParticipant`
--

CREATE TABLE `CourtCaseSessionParticipant` (
  `courtCaseSessionId` int UNSIGNED DEFAULT NULL,
  `courtCaseParticipantId` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseSessions`
--

CREATE TABLE `CourtCaseSessions` (
  `courtCaseSessionId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `roomId` int UNSIGNED DEFAULT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `repoerterId` int UNSIGNED DEFAULT NULL,
  `startedAt` datetime DEFAULT NULL COMMENT 'prognozed end and started date',
  `endedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtCaseVerdicts`
--

CREATE TABLE `CourtCaseVerdicts` (
  `courtCaseVerdictId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `verdictTypeId` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtExpenses`
--

CREATE TABLE `CourtExpenses` (
  `courtExpenseId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `causerId` int UNSIGNED DEFAULT NULL,
  `causerType` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `attachments` json DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `CourtTypes`
--

CREATE TABLE `CourtTypes` (
  `courtTypeId` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `EvidenceRequirements`
--

CREATE TABLE `EvidenceRequirements` (
  `evidenceRequirementId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `evidenceBringerType` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evidenceType` enum('testimony','expertConclusion','written','material') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '3.6.8',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Expertises`
--

CREATE TABLE `Expertises` (
  `expertiseId` int UNSIGNED DEFAULT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `type` enum('initial','double','additional') COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `FailedJobs`
--

CREATE TABLE `FailedJobs` (
  `id` int UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `FirstInstanceCourtCaseDetails`
--

CREATE TABLE `FirstInstanceCourtCaseDetails` (
  `firstInstanceCourtCaseDetailId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `relatedId` int UNSIGNED DEFAULT NULL COMMENT 'parent id',
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `InvitationUsers`
--

CREATE TABLE `InvitationUsers` (
  `invitationUserId` int UNSIGNED NOT NULL,
  `userId` int UNSIGNED NOT NULL,
  `inviteUrl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passed` datetime NOT NULL,
  `psnInfo` json DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Migrations`
--

CREATE TABLE `Migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `OauthAccessTokens`
--

CREATE TABLE `OauthAccessTokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `client_id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `OauthAuthCodes`
--

CREATE TABLE `OauthAuthCodes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `client_id` int UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `OauthClients`
--

CREATE TABLE `OauthClients` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `OauthPersonalAccessClients`
--

CREATE TABLE `OauthPersonalAccessClients` (
  `id` int UNSIGNED NOT NULL,
  `client_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `OauthRefreshTokens`
--

CREATE TABLE `OauthRefreshTokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Permissions`
--

CREATE TABLE `Permissions` (
  `permissionId` int UNSIGNED NOT NULL,
  `permissionName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissionDescription` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissionActive` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Producers`
--

CREATE TABLE `Producers` (
  `producerId` int UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rememberToken` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verifiedAt` datetime NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Profiles`
--

CREATE TABLE `Profiles` (
  `profileId` int UNSIGNED NOT NULL,
  `psn` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstName` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patronymic` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateBirth` datetime DEFAULT NULL,
  `gender` enum('M','F') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Representatives`
--

CREATE TABLE `Representatives` (
  `representativeId` int UNSIGNED NOT NULL,
  `citizenId` int UNSIGNED DEFAULT NULL,
  `companyId` int UNSIGNED DEFAULT NULL,
  `attachmentPath` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `RolePermission`
--

CREATE TABLE `RolePermission` (
  `roleId` int UNSIGNED NOT NULL,
  `permissionId` int UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Roles`
--

CREATE TABLE `Roles` (
  `roleId` int UNSIGNED NOT NULL,
  `roleName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleDescription` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleActive` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Rooms`
--

CREATE TABLE `Rooms` (
  `roomId` int UNSIGNED NOT NULL,
  `attributeId` int UNSIGNED DEFAULT NULL,
  `name` char(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Sessions`
--

CREATE TABLE `Sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `SolicitationPositions`
--

CREATE TABLE `SolicitationPositions` (
  `solicitationPositionId` int UNSIGNED NOT NULL,
  `courtCaseParticipantId` int UNSIGNED DEFAULT NULL,
  `causerId` int UNSIGNED DEFAULT NULL,
  `causerType` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Solicitations`
--

CREATE TABLE `Solicitations` (
  `solicitationsId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `typeId` int UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `files` json DEFAULT NULL,
  `causerId` int UNSIGNED DEFAULT NULL,
  `causerType` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='causer is a user and citizens erlations';

-- --------------------------------------------------------

--
-- Структура таблицы `SolicitationTypes`
--

CREATE TABLE `SolicitationTypes` (
  `solicitationTypeId` int UNSIGNED NOT NULL,
  `courtTypeId` int UNSIGNED DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` int UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `telescope_entries_tags`
--

CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Translators`
--

CREATE TABLE `Translators` (
  `translatorId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `courtCaseParticipantId` int UNSIGNED DEFAULT NULL,
  `fullName` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `UserPermission`
--

CREATE TABLE `UserPermission` (
  `userId` int UNSIGNED NOT NULL,
  `permissionId` int UNSIGNED NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `userId` int UNSIGNED NOT NULL,
  `roleId` int UNSIGNED DEFAULT NULL,
  `profileId` int UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passwordHash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rememberToken` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `deletedAt` datetime DEFAULT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main users when has roles, attributes ex. (Court,...)';

-- --------------------------------------------------------

--
-- Структура таблицы `VerdictTypes`
--

CREATE TABLE `VerdictTypes` (
  `verdictTypeId` int UNSIGNED NOT NULL,
  `name` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` char(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `WItnesses`
--

CREATE TABLE `WItnesses` (
  `wItnessId` int UNSIGNED NOT NULL,
  `courtCaseId` int UNSIGNED DEFAULT NULL,
  `judgeId` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `type` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `AppealAnswers`
--
ALTER TABLE `AppealAnswers`
  ADD PRIMARY KEY (`courtCaseAppealAnswerId`),
  ADD KEY `courtcaseanswers___fk_court_id` (`courtCaseId`),
  ADD KEY `courtcaseanswers___fk_court_case_participant_id` (`courtCaseParticipantId`);

--
-- Индексы таблицы `AppealCourtCaseDetails`
--
ALTER TABLE `AppealCourtCaseDetails`
  ADD PRIMARY KEY (`appealCourtCaseDetailId`),
  ADD KEY `appealcourtcasedetails___fk_court_case_id` (`courtCaseId`);

--
-- Индексы таблицы `Attributes`
--
ALTER TABLE `Attributes`
  ADD PRIMARY KEY (`attributeId`),
  ADD UNIQUE KEY `attributes_attributename_unique` (`attributeName`),
  ADD KEY `foreign_attributes_types_type_id` (`typeId`);

--
-- Индексы таблицы `AttributeUser`
--
ALTER TABLE `AttributeUser`
  ADD PRIMARY KEY (`userId`,`attributeId`),
  ADD KEY `foreign_attribute_attributes_attribute_attribute_id` (`attributeId`);

--
-- Индексы таблицы `CassationCourtCaseDetails`
--
ALTER TABLE `CassationCourtCaseDetails`
  ADD PRIMARY KEY (`cassationCourtCaseDetailId`),
  ADD KEY `cassationcourtcasedetails___fk_courtcasse` (`courtCaseId`);

--
-- Индексы таблицы `Citizens`
--
ALTER TABLE `Citizens`
  ADD PRIMARY KEY (`citizenId`),
  ADD KEY `foreign_citizens_users_user_id` (`userId`),
  ADD KEY `foreign_citizens_profiles_profile_id` (`profileId`);

--
-- Индексы таблицы `Clients`
--
ALTER TABLE `Clients`
  ADD PRIMARY KEY (`clientId`),
  ADD UNIQUE KEY `clients_clientname_unique` (`clientName`);

--
-- Индексы таблицы `ClientUserMapping`
--
ALTER TABLE `ClientUserMapping`
  ADD PRIMARY KEY (`mappingId`),
  ADD KEY `foreign_client_user_mapping_client_id` (`clientId`),
  ADD KEY `foreign_client_user_mapping_user_id` (`userId`);

--
-- Индексы таблицы `Companies`
--
ALTER TABLE `Companies`
  ADD PRIMARY KEY (`companyId`),
  ADD KEY `companies___fk_executive_id` (`executiveId`);

--
-- Индексы таблицы `CourtCaseAnswers`
--
ALTER TABLE `CourtCaseAnswers`
  ADD PRIMARY KEY (`courtCaseAnswerId`),
  ADD KEY `courtcaseanswers___fk_court_case` (`courtCaseId`);

--
-- Индексы таблицы `CourtCaseAttachedDetached`
--
ALTER TABLE `CourtCaseAttachedDetached`
  ADD KEY `courtcaseattacheddetached___fk_courtcase_from_id_` (`fromId`),
  ADD KEY `courtcaseattacheddetached___fk_courtcasetoid` (`toId`);

--
-- Индексы таблицы `CourtCaseDecisions`
--
ALTER TABLE `CourtCaseDecisions`
  ADD PRIMARY KEY (`courtCaseDecisionId`),
  ADD KEY `courtcasedecisions___fk_cort_case` (`courtCaseId`),
  ADD KEY `courtcasedecisions___fk_judge_id` (`judgeId`);

--
-- Индексы таблицы `CourtCaseDecisionTypes`
--
ALTER TABLE `CourtCaseDecisionTypes`
  ADD PRIMARY KEY (`courtCaseDecisionTypeId`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `courtcasedecisiontypes___fk_court_type_id` (`courtTypeId`);

--
-- Индексы таблицы `CourtCaseOfficeActs`
--
ALTER TABLE `CourtCaseOfficeActs`
  ADD PRIMARY KEY (`courtCaseOfficeActId`);

--
-- Индексы таблицы `CourtCaseParticipants`
--
ALTER TABLE `CourtCaseParticipants`
  ADD PRIMARY KEY (`courtCaseParticipantId`),
  ADD KEY `courtcaseparticipants___fk_ewfgvefgew` (`cortCaseParticipantTypeId`),
  ADD KEY `courtcaseparticipants_courtcases_courtcaseid_fk_id` (`courtCaseId`),
  ADD KEY `courtcaseparticipants___fk_citizen_id` (`citizenId`),
  ADD KEY `courtcaseparticipants___fk_user_id` (`userId`),
  ADD KEY `courtcaseparticipants___fk_company_id` (`companyId`);

--
-- Индексы таблицы `CourtCaseParticipantTypes`
--
ALTER TABLE `CourtCaseParticipantTypes`
  ADD PRIMARY KEY (`courtCaseParticipantTypeId`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `CourtCaseRepresentatives`
--
ALTER TABLE `CourtCaseRepresentatives`
  ADD PRIMARY KEY (`courtCaseRepresentativeId`),
  ADD KEY `courtcaserepresentatives___fk_participant_id` (`courtCaseParticipantId`),
  ADD KEY `courtcaserepresentatives___fk_citizen_id` (`citizenId`);

--
-- Индексы таблицы `CourtCases`
--
ALTER TABLE `CourtCases`
  ADD PRIMARY KEY (`courtCaseId`),
  ADD KEY `courtcases___fk_users` (`judgeId`),
  ADD KEY `courtcases___fk_court_type_id` (`courtTypeid`);

--
-- Индексы таблицы `CourtCaseSessionParticipant`
--
ALTER TABLE `CourtCaseSessionParticipant`
  ADD KEY `courtcasesessionparticipant___fk_session` (`courtCaseSessionId`),
  ADD KEY `courtcasesessionparticipant___fk_participoant` (`courtCaseParticipantId`);

--
-- Индексы таблицы `CourtCaseSessions`
--
ALTER TABLE `CourtCaseSessions`
  ADD PRIMARY KEY (`courtCaseSessionId`),
  ADD KEY `courtcasesessions___fk_courtcase` (`courtCaseId`),
  ADD KEY `courtcasesessions___fk_room_id` (`roomId`),
  ADD KEY `courtcasesessions___fk_judge` (`judgeId`),
  ADD KEY `courtcasesessions___fk_reporteer` (`repoerterId`);

--
-- Индексы таблицы `CourtCaseVerdicts`
--
ALTER TABLE `CourtCaseVerdicts`
  ADD PRIMARY KEY (`courtCaseVerdictId`),
  ADD KEY `courtcaseverdicts___fk_court` (`courtCaseId`),
  ADD KEY `courtcaseverdicts___fk_judge` (`judgeId`),
  ADD KEY `courtcaseverdicts___fk_verdict` (`verdictTypeId`);

--
-- Индексы таблицы `CourtExpenses`
--
ALTER TABLE `CourtExpenses`
  ADD PRIMARY KEY (`courtExpenseId`),
  ADD KEY `courtexpenses___fk_causer_iewfewfewewfew232d` (`courtCaseId`);

--
-- Индексы таблицы `CourtTypes`
--
ALTER TABLE `CourtTypes`
  ADD PRIMARY KEY (`courtTypeId`),
  ADD UNIQUE KEY `attributetypes_name_unique` (`name`);

--
-- Индексы таблицы `EvidenceRequirements`
--
ALTER TABLE `EvidenceRequirements`
  ADD PRIMARY KEY (`evidenceRequirementId`);

--
-- Индексы таблицы `Expertises`
--
ALTER TABLE `Expertises`
  ADD KEY `expertise___fk_court_case_id` (`courtCaseId`),
  ADD KEY `expertise___fk_judge_id` (`judgeId`);

--
-- Индексы таблицы `FailedJobs`
--
ALTER TABLE `FailedJobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failedjobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `FirstInstanceCourtCaseDetails`
--
ALTER TABLE `FirstInstanceCourtCaseDetails`
  ADD PRIMARY KEY (`firstInstanceCourtCaseDetailId`),
  ADD KEY `firstinstancecourtcasedetails_courtcases_courtcaseid_fk` (`courtCaseId`),
  ADD KEY `firstinstancecourtcasedetails___fk_parent_id` (`relatedId`);

--
-- Индексы таблицы `InvitationUsers`
--
ALTER TABLE `InvitationUsers`
  ADD PRIMARY KEY (`invitationUserId`),
  ADD UNIQUE KEY `invitationusers_inviteurl_unique` (`inviteUrl`),
  ADD KEY `foreign_invitation_users_user_id` (`userId`);

--
-- Индексы таблицы `Migrations`
--
ALTER TABLE `Migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `OauthAccessTokens`
--
ALTER TABLE `OauthAccessTokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauthaccesstokens_user_id_index` (`user_id`);

--
-- Индексы таблицы `OauthAuthCodes`
--
ALTER TABLE `OauthAuthCodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauthauthcodes_user_id_index` (`user_id`);

--
-- Индексы таблицы `OauthClients`
--
ALTER TABLE `OauthClients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauthclients_user_id_index` (`user_id`);

--
-- Индексы таблицы `OauthPersonalAccessClients`
--
ALTER TABLE `OauthPersonalAccessClients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `OauthRefreshTokens`
--
ALTER TABLE `OauthRefreshTokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauthrefreshtokens_access_token_id_index` (`access_token_id`);

--
-- Индексы таблицы `Permissions`
--
ALTER TABLE `Permissions`
  ADD PRIMARY KEY (`permissionId`),
  ADD UNIQUE KEY `permissions_permissionname_unique` (`permissionName`),
  ADD KEY `indexpermissiondescription` (`permissionDescription`);

--
-- Индексы таблицы `Producers`
--
ALTER TABLE `Producers`
  ADD PRIMARY KEY (`producerId`),
  ADD UNIQUE KEY `producers_email_unique` (`email`),
  ADD UNIQUE KEY `producers_username_unique` (`username`);

--
-- Индексы таблицы `Profiles`
--
ALTER TABLE `Profiles`
  ADD PRIMARY KEY (`profileId`);

--
-- Индексы таблицы `Representatives`
--
ALTER TABLE `Representatives`
  ADD PRIMARY KEY (`representativeId`),
  ADD KEY `representatives___fk_citizen_id` (`citizenId`),
  ADD KEY `representatives___fk_company_id` (`companyId`);

--
-- Индексы таблицы `RolePermission`
--
ALTER TABLE `RolePermission`
  ADD PRIMARY KEY (`roleId`,`permissionId`),
  ADD KEY `foreign_role_permission_permissions_permission_id` (`permissionId`);

--
-- Индексы таблицы `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`roleId`),
  ADD UNIQUE KEY `roles_rolename_unique` (`roleName`),
  ADD KEY `indexrolesdescription` (`roleDescription`);

--
-- Индексы таблицы `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`roomId`),
  ADD KEY `rooms___fk_attribute` (`attributeId`);

--
-- Индексы таблицы `Sessions`
--
ALTER TABLE `Sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `SolicitationPositions`
--
ALTER TABLE `SolicitationPositions`
  ADD PRIMARY KEY (`solicitationPositionId`),
  ADD KEY `solicitationpositions___fk_court_participant_id` (`courtCaseParticipantId`);

--
-- Индексы таблицы `Solicitations`
--
ALTER TABLE `Solicitations`
  ADD PRIMARY KEY (`solicitationsId`),
  ADD KEY `solicitations___fk_court_caseid` (`courtCaseId`),
  ADD KEY `solicitations___fk_type_id` (`typeId`);

--
-- Индексы таблицы `SolicitationTypes`
--
ALTER TABLE `SolicitationTypes`
  ADD PRIMARY KEY (`solicitationTypeId`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`);

--
-- Индексы таблицы `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD PRIMARY KEY (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Индексы таблицы `telescope_monitoring`
--
ALTER TABLE `telescope_monitoring`
  ADD PRIMARY KEY (`tag`);

--
-- Индексы таблицы `Translators`
--
ALTER TABLE `Translators`
  ADD PRIMARY KEY (`translatorId`),
  ADD KEY `translators___fk_cortcaseid` (`courtCaseId`),
  ADD KEY `translators___fk_participant` (`courtCaseParticipantId`);

--
-- Индексы таблицы `UserPermission`
--
ALTER TABLE `UserPermission`
  ADD PRIMARY KEY (`permissionId`,`userId`),
  ADD KEY `foreign_user_permission_user_id` (`userId`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `foreign_users_role_role_id` (`roleId`),
  ADD KEY `foreign_profiles_profile_id` (`profileId`);

--
-- Индексы таблицы `VerdictTypes`
--
ALTER TABLE `VerdictTypes`
  ADD PRIMARY KEY (`verdictTypeId`);

--
-- Индексы таблицы `WItnesses`
--
ALTER TABLE `WItnesses`
  ADD PRIMARY KEY (`wItnessId`),
  ADD KEY `witnesses___fk_court_case_id` (`courtCaseId`),
  ADD KEY `witness___fk_judge_id` (`judgeId`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `AppealAnswers`
--
ALTER TABLE `AppealAnswers`
  MODIFY `courtCaseAppealAnswerId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `AppealCourtCaseDetails`
--
ALTER TABLE `AppealCourtCaseDetails`
  MODIFY `appealCourtCaseDetailId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Attributes`
--
ALTER TABLE `Attributes`
  MODIFY `attributeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CassationCourtCaseDetails`
--
ALTER TABLE `CassationCourtCaseDetails`
  MODIFY `cassationCourtCaseDetailId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Citizens`
--
ALTER TABLE `Citizens`
  MODIFY `citizenId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Clients`
--
ALTER TABLE `Clients`
  MODIFY `clientId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ClientUserMapping`
--
ALTER TABLE `ClientUserMapping`
  MODIFY `mappingId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Companies`
--
ALTER TABLE `Companies`
  MODIFY `companyId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseAnswers`
--
ALTER TABLE `CourtCaseAnswers`
  MODIFY `courtCaseAnswerId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseDecisions`
--
ALTER TABLE `CourtCaseDecisions`
  MODIFY `courtCaseDecisionId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseDecisionTypes`
--
ALTER TABLE `CourtCaseDecisionTypes`
  MODIFY `courtCaseDecisionTypeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseOfficeActs`
--
ALTER TABLE `CourtCaseOfficeActs`
  MODIFY `courtCaseOfficeActId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseParticipants`
--
ALTER TABLE `CourtCaseParticipants`
  MODIFY `courtCaseParticipantId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseParticipantTypes`
--
ALTER TABLE `CourtCaseParticipantTypes`
  MODIFY `courtCaseParticipantTypeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseRepresentatives`
--
ALTER TABLE `CourtCaseRepresentatives`
  MODIFY `courtCaseRepresentativeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCases`
--
ALTER TABLE `CourtCases`
  MODIFY `courtCaseId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseSessions`
--
ALTER TABLE `CourtCaseSessions`
  MODIFY `courtCaseSessionId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtCaseVerdicts`
--
ALTER TABLE `CourtCaseVerdicts`
  MODIFY `courtCaseVerdictId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtExpenses`
--
ALTER TABLE `CourtExpenses`
  MODIFY `courtExpenseId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `CourtTypes`
--
ALTER TABLE `CourtTypes`
  MODIFY `courtTypeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `EvidenceRequirements`
--
ALTER TABLE `EvidenceRequirements`
  MODIFY `evidenceRequirementId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `FailedJobs`
--
ALTER TABLE `FailedJobs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `FirstInstanceCourtCaseDetails`
--
ALTER TABLE `FirstInstanceCourtCaseDetails`
  MODIFY `firstInstanceCourtCaseDetailId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `InvitationUsers`
--
ALTER TABLE `InvitationUsers`
  MODIFY `invitationUserId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Migrations`
--
ALTER TABLE `Migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT для таблицы `OauthClients`
--
ALTER TABLE `OauthClients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `OauthPersonalAccessClients`
--
ALTER TABLE `OauthPersonalAccessClients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Permissions`
--
ALTER TABLE `Permissions`
  MODIFY `permissionId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Producers`
--
ALTER TABLE `Producers`
  MODIFY `producerId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Profiles`
--
ALTER TABLE `Profiles`
  MODIFY `profileId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Representatives`
--
ALTER TABLE `Representatives`
  MODIFY `representativeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Roles`
--
ALTER TABLE `Roles`
  MODIFY `roleId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `roomId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SolicitationPositions`
--
ALTER TABLE `SolicitationPositions`
  MODIFY `solicitationPositionId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Solicitations`
--
ALTER TABLE `Solicitations`
  MODIFY `solicitationsId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SolicitationTypes`
--
ALTER TABLE `SolicitationTypes`
  MODIFY `solicitationTypeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `Translators`
--
ALTER TABLE `Translators`
  MODIFY `translatorId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `userId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `VerdictTypes`
--
ALTER TABLE `VerdictTypes`
  MODIFY `verdictTypeId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `WItnesses`
--
ALTER TABLE `WItnesses`
  MODIFY `wItnessId` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `AppealAnswers`
--
ALTER TABLE `AppealAnswers`
  ADD CONSTRAINT `CourtCaseAnswers___fk_court_case_participant_id` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipants` (`courtCaseParticipantId`),
  ADD CONSTRAINT `CourtCaseAnswers___fk_court_id` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `AppealCourtCaseDetails`
--
ALTER TABLE `AppealCourtCaseDetails`
  ADD CONSTRAINT `AppealCourtCaseDetails___fk_court_case_id` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `Attributes`
--
ALTER TABLE `Attributes`
  ADD CONSTRAINT `foreign_attributes_types_type_id` FOREIGN KEY (`typeId`) REFERENCES `CourtTypes` (`courtTypeId`);

--
-- Ограничения внешнего ключа таблицы `AttributeUser`
--
ALTER TABLE `AttributeUser`
  ADD CONSTRAINT `foreign_attribute_attributes_attribute_attribute_id` FOREIGN KEY (`attributeId`) REFERENCES `Attributes` (`attributeId`) ON DELETE CASCADE,
  ADD CONSTRAINT `foreign_attribute_user_users_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `CassationCourtCaseDetails`
--
ALTER TABLE `CassationCourtCaseDetails`
  ADD CONSTRAINT `CassationCourtCaseDetails___fk_courtCasse` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `Citizens`
--
ALTER TABLE `Citizens`
  ADD CONSTRAINT `foreign_citizens_profiles_profile_id` FOREIGN KEY (`profileId`) REFERENCES `Profiles` (`profileId`) ON DELETE CASCADE,
  ADD CONSTRAINT `foreign_citizens_users_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `ClientUserMapping`
--
ALTER TABLE `ClientUserMapping`
  ADD CONSTRAINT `foreign_client_user_mapping_client_id` FOREIGN KEY (`clientId`) REFERENCES `Clients` (`clientId`),
  ADD CONSTRAINT `foreign_client_user_mapping_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `Companies`
--
ALTER TABLE `Companies`
  ADD CONSTRAINT `Companies___fk_executive_id` FOREIGN KEY (`executiveId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseAnswers`
--
ALTER TABLE `CourtCaseAnswers`
  ADD CONSTRAINT `CourtCaseAnswers___fk_court_case` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseAttachedDetached`
--
ALTER TABLE `CourtCaseAttachedDetached`
  ADD CONSTRAINT `CourtCaseAttachedDetached___fk_courtCase_from_id_` FOREIGN KEY (`fromId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `CourtCaseAttachedDetached___fk_courtCaseToId` FOREIGN KEY (`toId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseDecisions`
--
ALTER TABLE `CourtCaseDecisions`
  ADD CONSTRAINT `CourtCaseDecisions___fk_cort_case` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `CourtCaseDecisions___fk_judge_id` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseDecisionTypes`
--
ALTER TABLE `CourtCaseDecisionTypes`
  ADD CONSTRAINT `CourtCaseDecisionTypes___fk_court_type_id` FOREIGN KEY (`courtTypeId`) REFERENCES `CourtTypes` (`courtTypeId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseParticipants`
--
ALTER TABLE `CourtCaseParticipants`
  ADD CONSTRAINT `CourtCaseParticipants___fk_citizen_id` FOREIGN KEY (`citizenId`) REFERENCES `Citizens` (`citizenId`),
  ADD CONSTRAINT `CourtCaseParticipants___fk_company_id` FOREIGN KEY (`companyId`) REFERENCES `Companies` (`companyId`),
  ADD CONSTRAINT `CourtCaseParticipants___fk_ewfgvefgew` FOREIGN KEY (`cortCaseParticipantTypeId`) REFERENCES `CourtCaseParticipantTypes` (`courtCaseParticipantTypeId`),
  ADD CONSTRAINT `CourtCaseParticipants___fk_participant_id` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipantTypes` (`courtCaseParticipantTypeId`),
  ADD CONSTRAINT `CourtCaseParticipants___fk_participant_type_id` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipantTypes` (`courtCaseParticipantTypeId`),
  ADD CONSTRAINT `CourtCaseParticipants___fk_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `CourtCaseParticipants_CourtCases_courtCaseId_fk` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseRepresentatives`
--
ALTER TABLE `CourtCaseRepresentatives`
  ADD CONSTRAINT `CourtCaseRepresentatives___fk_citizen_id` FOREIGN KEY (`citizenId`) REFERENCES `Citizens` (`citizenId`),
  ADD CONSTRAINT `CourtCaseRepresentatives___fk_participant_id` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipants` (`courtCaseParticipantId`);

--
-- Ограничения внешнего ключа таблицы `CourtCases`
--
ALTER TABLE `CourtCases`
  ADD CONSTRAINT `CourtCase_CourtTypes_courtTypeId_fk` FOREIGN KEY (`courtTypeid`) REFERENCES `CourtTypes` (`courtTypeId`),
  ADD CONSTRAINT `CourtCases___fk_court_type_id` FOREIGN KEY (`courtTypeid`) REFERENCES `CourtCaseParticipantTypes` (`courtCaseParticipantTypeId`),
  ADD CONSTRAINT `CourtCases___fk_users` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseSessionParticipant`
--
ALTER TABLE `CourtCaseSessionParticipant`
  ADD CONSTRAINT `CourtCaseSessionParticipant___fk_participoant` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipants` (`courtCaseParticipantId`),
  ADD CONSTRAINT `CourtCaseSessionParticipant___fk_session` FOREIGN KEY (`courtCaseSessionId`) REFERENCES `CourtCaseSessions` (`courtCaseSessionId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseSessions`
--
ALTER TABLE `CourtCaseSessions`
  ADD CONSTRAINT `CourtCaseSessions___fk_courtCase` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `CourtCaseSessions___fk_judge` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `CourtCaseSessions___fk_reporteer` FOREIGN KEY (`repoerterId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `CourtCaseSessions___fk_room_id` FOREIGN KEY (`roomId`) REFERENCES `Rooms` (`roomId`);

--
-- Ограничения внешнего ключа таблицы `CourtCaseVerdicts`
--
ALTER TABLE `CourtCaseVerdicts`
  ADD CONSTRAINT `CourtCaseVerdicts___fk_court` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `CourtCaseVerdicts___fk_judge` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `CourtCaseVerdicts___fk_verdict` FOREIGN KEY (`verdictTypeId`) REFERENCES `VerdictTypes` (`verdictTypeId`);

--
-- Ограничения внешнего ключа таблицы `CourtExpenses`
--
ALTER TABLE `CourtExpenses`
  ADD CONSTRAINT `CourtExpenses___fk_causer_iewfewfewewfew232d` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `Expertises`
--
ALTER TABLE `Expertises`
  ADD CONSTRAINT `Expertise___fk_court_case_id` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `Expertise___fk_judge_id` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `FirstInstanceCourtCaseDetails`
--
ALTER TABLE `FirstInstanceCourtCaseDetails`
  ADD CONSTRAINT `FirstInstanceCourtCaseDetails___fk_parent_id` FOREIGN KEY (`relatedId`) REFERENCES `FirstInstanceCourtCaseDetails` (`firstInstanceCourtCaseDetailId`),
  ADD CONSTRAINT `FirstInstanceCourtCaseDetails_CourtCases_courtCaseId_fk` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);

--
-- Ограничения внешнего ключа таблицы `InvitationUsers`
--
ALTER TABLE `InvitationUsers`
  ADD CONSTRAINT `foreign_invitation_users_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Representatives`
--
ALTER TABLE `Representatives`
  ADD CONSTRAINT `Representatives___fk_citizen_id` FOREIGN KEY (`citizenId`) REFERENCES `Citizens` (`citizenId`),
  ADD CONSTRAINT `Representatives___fk_company_id` FOREIGN KEY (`companyId`) REFERENCES `Companies` (`companyId`);

--
-- Ограничения внешнего ключа таблицы `RolePermission`
--
ALTER TABLE `RolePermission`
  ADD CONSTRAINT `foreign_role_permission_permissions_permission_id` FOREIGN KEY (`permissionId`) REFERENCES `Permissions` (`permissionId`),
  ADD CONSTRAINT `foreign_role_permission_roles_role_id` FOREIGN KEY (`roleId`) REFERENCES `Roles` (`roleId`);

--
-- Ограничения внешнего ключа таблицы `Rooms`
--
ALTER TABLE `Rooms`
  ADD CONSTRAINT `Rooms___fk_attribute` FOREIGN KEY (`attributeId`) REFERENCES `Attributes` (`attributeId`);

--
-- Ограничения внешнего ключа таблицы `SolicitationPositions`
--
ALTER TABLE `SolicitationPositions`
  ADD CONSTRAINT `SolicitationPositions___fk_court_participant_id` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipants` (`courtCaseParticipantId`);

--
-- Ограничения внешнего ключа таблицы `Solicitations`
--
ALTER TABLE `Solicitations`
  ADD CONSTRAINT `Solicitations___fk_court_caseId` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `Solicitations___fk_type_id` FOREIGN KEY (`typeId`) REFERENCES `SolicitationTypes` (`solicitationTypeId`);

--
-- Ограничения внешнего ключа таблицы `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Translators`
--
ALTER TABLE `Translators`
  ADD CONSTRAINT `Translators___fk_cortCaseId` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`),
  ADD CONSTRAINT `Translators___fk_participant` FOREIGN KEY (`courtCaseParticipantId`) REFERENCES `CourtCaseParticipants` (`courtCaseParticipantId`);

--
-- Ограничения внешнего ключа таблицы `UserPermission`
--
ALTER TABLE `UserPermission`
  ADD CONSTRAINT `foreign_user_permission_permission_id` FOREIGN KEY (`permissionId`) REFERENCES `Permissions` (`permissionId`),
  ADD CONSTRAINT `foreign_user_permission_user_id` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`);

--
-- Ограничения внешнего ключа таблицы `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `foreign_profiles_profile_id` FOREIGN KEY (`profileId`) REFERENCES `Profiles` (`profileId`),
  ADD CONSTRAINT `foreign_users_role_role_id` FOREIGN KEY (`roleId`) REFERENCES `Roles` (`roleId`);

--
-- Ограничения внешнего ключа таблицы `WItnesses`
--
ALTER TABLE `WItnesses`
  ADD CONSTRAINT `WItness___fk_judge_id` FOREIGN KEY (`judgeId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `WItnesses___fk_court_case_id` FOREIGN KEY (`courtCaseId`) REFERENCES `CourtCases` (`courtCaseId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
