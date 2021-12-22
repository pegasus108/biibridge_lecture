/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

USE sample_db;

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `access_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `url` text NOT NULL,
  `is_depth` int(11) NOT NULL DEFAULT '0',
  `allow` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`access_no`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `access_book`;
CREATE TABLE `access_book` (
  `access_book_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`access_book_no`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `author_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `kana` text,
  `image` text,
  `value` text,
  `s_name` text,
  `s_kana` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`author_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `author_type`;
CREATE TABLE `author_type` (
  `author_type_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`author_type_no`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `banner_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `url` text,
  `image` text NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `display_order` int(11) NOT NULL DEFAULT '0',
  `target` int(11) DEFAULT NULL,
  `place` int(11) DEFAULT '1',
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`banner_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `banner_big`;
CREATE TABLE `banner_big` (
  `banner_big_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `url` text,
  `image` text NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `display_order` int(11) NOT NULL DEFAULT '0',
  `target` int(11) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`banner_big_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `book_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) DEFAULT NULL,
  `name` text,
  `kana` text,
  `volume` text,
  `sub_name` text,
  `sub_kana` text,
  `image` text,
  `isbn` text,
  `e_isbn` text,
  `magazine_code` text,
  `c_code` text,
  `book_date` datetime DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `version` text,
  `book_size_no` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `outline` text,
  `outline_abr` text,
  `explain` text,
  `content` text,
  `public_date` datetime DEFAULT NULL,
  `public_date_order` int(11) DEFAULT NULL,
  `public_status` int(11) DEFAULT NULL,
  `new_status` int(11) DEFAULT NULL,
  `new_order` int(11) DEFAULT NULL,
  `ebook_status` int(11) DEFAULT NULL,
  `book_format` int(11) DEFAULT NULL,
  `book_format_other` text,
  `keyword` text,
  `next_book` int(11) DEFAULT NULL,
  `recommend_status` int(11) DEFAULT NULL,
  `recommend_sentence` text,
  `recommend_order` int(11) DEFAULT NULL,
  `yondemill_id` int(11) DEFAULT NULL,
  `yondemill_file` text,
  `yondemill_created_at` datetime DEFAULT NULL,
  `yondemill_book_sales_url` text,
  `stock_status_no` int(11) DEFAULT NULL,
  `cart_status` int(11) DEFAULT NULL,
  `freeitem` text,
  `sync_allowed` datetime DEFAULT NULL,
  `synced` datetime DEFAULT NULL,
  `image_posted` datetime DEFAULT NULL,
  `note` text,
  `book_width` int(11) DEFAULT NULL,
  `book_cover` int(11) DEFAULT NULL,
  `book_band` int(11) DEFAULT NULL,
  `book_slip` int(11) DEFAULT NULL,
  `copyright` mediumtext,
  `ebook_price` int(11) DEFAULT NULL,
  `note_epub` mediumtext,
  `note_image` mediumtext,
  `note_logo` mediumtext,
  `note_text` mediumtext,
  `product_code` text,
  `printoffice` text,
  `sp_printoffice` text,
  `first_edition_issue_date` date DEFAULT NULL,
  `first_edition_circulation_number` int(11) DEFAULT NULL,
  `questionnaire_url` text,
  `c_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `book_size_no` (`book_size_no`),
  KEY `stock_status_no` (`stock_status_no`),
  KEY `publisher_no_2` (`publisher_no`,`public_status`,`public_date`,`new_status`,`book_date`),
  KEY `stock_status_no_2` (`publisher_no`,`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_ebookstores`;
CREATE TABLE `book_ebookstores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `ebookstores_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `public_status` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NULL DEFAULT NULL,
  `u_stamp` timestamp NULL DEFAULT NULL,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_no` (`book_no`,`ebookstores_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_field`;
CREATE TABLE `book_field` (
  `book_field_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `category` text NOT NULL,
  PRIMARY KEY (`book_field_no`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_format`;
CREATE TABLE `book_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_format_book`;
CREATE TABLE `book_format_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `book_no_other` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NULL DEFAULT NULL,
  `u_stamp` timestamp NULL DEFAULT NULL,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_genre`;
CREATE TABLE `book_genre` (
  `book_genre_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `genre_no` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_genre_no`),
  KEY `book_no` (`book_no`),
  KEY `genre_no` (`genre_no`),
  KEY `book_no_genre_no` (`book_no`,`genre_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_honzuki`;
CREATE TABLE `book_honzuki` (
  `book_no` int(11) NOT NULL AUTO_INCREMENT,
  `is_enable` int(11) NOT NULL,
  `url` text,
  `review_count` int(11) DEFAULT NULL,
  `avg_rate` int(11) DEFAULT NULL,
  `has_backlink` int(11) DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_jpo`;
CREATE TABLE `book_jpo` (
  `book_no` int(11) NOT NULL AUTO_INCREMENT,
  `measure_height` text CHARACTER SET ujis,
  `measure_width` text CHARACTER SET ujis,
  `measure_thickness` int(11) DEFAULT NULL,
  `subject_code` text CHARACTER SET ujis,
  `imprint_name` text CHARACTER SET ujis,
  `imprint_collationkey` text CHARACTER SET ujis,
  `extent_value` int(11) DEFAULT NULL,
  `unpriced_item_type` text CHARACTER SET ujis,
  `price_amount` int(11) DEFAULT NULL,
  `price_effective_from` date DEFAULT NULL,
  `price_effective_until` date DEFAULT NULL,
  `on_sale_date` date DEFAULT NULL,
  `pre_order_limit` date DEFAULT NULL,
  `announcement_date` date DEFAULT NULL,
  `audience_code_value` text CHARACTER SET ujis,
  `audience_description` text,
  `long_description` text CHARACTER SET ujis,
  `recent_publication_author` text,
  `recent_publication_type` text,
  `recent_publication_reader` text,
  `recent_publication_content` text,
  `recent_publication_date` date DEFAULT NULL,
  `containeditem` text CHARACTER SET ujis,
  `product_form_description` text CHARACTER SET ujis,
  `promotional_text` text CHARACTER SET ujis,
  `language` text CHARACTER SET ujis,
  `reselling` text CHARACTER SET ujis,
  `reselling_date` date DEFAULT NULL,
  `resellingdatecheck` text,
  `supply_restriction_detail` text CHARACTER SET ujis,
  `notification_type` text,
  `jan_code` varchar(13) DEFAULT NULL,
  `publication_form` varchar(2) DEFAULT NULL,
  `monthly_issue` varchar(20) DEFAULT NULL,
  `completion` int(11) DEFAULT NULL,
  `each_volume_name` varchar(300) DEFAULT NULL,
  `each_volume_kana` varchar(300) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `Issued_date` date DEFAULT NULL,
  `library_selection_content` varchar(105) DEFAULT NULL,
  `Intermediary_company_handling` int(11) DEFAULT NULL,
  `extent` int(11) DEFAULT NULL,
  `award` varchar(30) DEFAULT NULL,
  `readers_write` int(11) DEFAULT NULL,
  `readers_write_page` int(11) DEFAULT NULL,
  `production_notes_item` varchar(2) DEFAULT NULL,
  `cd_dvd` int(11) DEFAULT NULL,
  `bond_name` varchar(10) DEFAULT NULL,
  `comments` varchar(200) DEFAULT NULL,
  `band_contents` varchar(100) DEFAULT NULL,
  `competition` varchar(100) DEFAULT NULL,
  `separate_material` varchar(100) DEFAULT NULL,
  `childrens_book_genre` varchar(2) DEFAULT NULL,
  `font_size` int(11) DEFAULT NULL,
  `ruby` int(11) DEFAULT NULL,
  `percentage_of_manga` int(11) DEFAULT NULL,
  `special_binding` varchar(100) DEFAULT NULL,
  `trick` varchar(100) DEFAULT NULL,
  `other_notices` int(11) DEFAULT NULL,
  `number_of_first_edition` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `return_deadline` date DEFAULT NULL,
  `band` int(11) DEFAULT NULL,
  `cover` int(11) DEFAULT NULL,
  `binding_place` varchar(100) DEFAULT NULL,
  `number_of_cohesions` int(11) DEFAULT NULL,
  PRIMARY KEY (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_label`;
CREATE TABLE `book_label` (
  `book_label_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `label_no` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_label_no`),
  KEY `book_no` (`book_no`),
  KEY `label_no` (`label_no`),
  KEY `book_no_label_no` (`book_no`,`label_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_relate`;
CREATE TABLE `book_relate` (
  `book_relate_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `book_relate_book_no` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_relate_no`),
  KEY `book_no` (`book_no`),
  KEY `book_relate_book_no` (`book_relate_book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_series`;
CREATE TABLE `book_series` (
  `book_series_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `series_no` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_series_no`),
  KEY `book_no` (`book_no`),
  KEY `series_no` (`series_no`),
  KEY `book_no_series_no` (`book_no`,`series_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `book_size`;
CREATE TABLE `book_size` (
  `book_size_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `order` int(11) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`book_size_no`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `campaign_site_link`;
CREATE TABLE `campaign_site_link` (
  `campaign_site_link_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) DEFAULT NULL,
  `book_no` int(11) NOT NULL,
  `url` text,
  `sort` int(11) NOT NULL,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`campaign_site_link_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `book_no` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `change_book_notice`;
CREATE TABLE `change_book_notice` (
  `change_book_notice_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `book_field_no` int(11) NOT NULL,
  PRIMARY KEY (`change_book_notice_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `company_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `company_category_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` mediumtext NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `public_date` datetime DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`company_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `company_category_no` (`company_category_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `company_category`;
CREATE TABLE `company_category` (
  `company_category_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `display` int(11) DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`company_category_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `company_fix_category`;
CREATE TABLE `company_fix_category` (
  `company_fix_category_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`company_fix_category_no`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `contact_no` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `title` text NOT NULL,
  `value` mediumtext NOT NULL,
  `c_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ebookstores`;
CREATE TABLE `ebookstores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `search_url` text,
  `charset` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `genre`;
CREATE TABLE `genre` (
  `genre_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `publisher_no` int(11) DEFAULT NULL,
  `display` int(11) DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`genre_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `info`;
CREATE TABLE `info` (
  `info_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `public_status` int(11) DEFAULT NULL,
  `public_date` datetime DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`info_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jpro_data`;
CREATE TABLE `jpro_data` (
  `book_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `notification_type` varchar(100) DEFAULT NULL,
  `jan_code` varchar(13) DEFAULT NULL,
  `magazine_code` varchar(7) DEFAULT NULL,
  `publication_form` varchar(2) DEFAULT NULL,
  `c_code` varchar(4) DEFAULT NULL,
  `subject_code` varchar(2) DEFAULT NULL,
  `book_name` varchar(300) DEFAULT NULL,
  `volume` varchar(20) DEFAULT NULL,
  `kana` varchar(300) DEFAULT NULL,
  `monthly_issue` varchar(20) DEFAULT NULL,
  `sub_name` varchar(300) DEFAULT NULL,
  `sub_kana` varchar(300) DEFAULT NULL,
  `imprint_name` varchar(300) DEFAULT NULL,
  `imprint_collationkey` varchar(300) DEFAULT NULL,
  `series_name` varchar(300) DEFAULT NULL,
  `series_kana` varchar(300) DEFAULT NULL,
  `completion` int(11) DEFAULT NULL,
  `each_volume_name` varchar(300) DEFAULT NULL,
  `each_volume_kana` varchar(300) DEFAULT NULL,
  `extent_value` int(11) DEFAULT NULL,
  `unpriced_item_type` varchar(2) DEFAULT NULL,
  `publisher_transaction_code` varchar(4) DEFAULT NULL,
  `publisher_name` varchar(100) DEFAULT NULL,
  `publisher_prefix` varchar(100) DEFAULT NULL,
  `selling_transaction_code` varchar(4) DEFAULT NULL,
  `selling_name` varchar(100) DEFAULT NULL,
  `selling_prefix` varchar(100) DEFAULT NULL,
  `language` varchar(30) DEFAULT NULL,
  `book_size_no` varchar(4) DEFAULT NULL,
  `measure_height` int(11) DEFAULT NULL,
  `measure_width` int(11) DEFAULT NULL,
  `measure_thickness` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `price_amount` int(11) DEFAULT NULL,
  `price_effective_until` date DEFAULT NULL,
  `reselling` int(11) DEFAULT NULL,
  `reselling_date` date DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `on_sale_date` date DEFAULT NULL,
  `Issued_date` date DEFAULT NULL,
  `audience_code_value` varchar(2) DEFAULT NULL,
  `audience_description` varchar(2) DEFAULT NULL,
  `outline` text,
  `long_description` text,
  `recent_publication` text,
  `library_selection_content` varchar(105) DEFAULT NULL,
  `explain` text,
  `keyword` varchar(250) DEFAULT NULL,
  `product_form_description` varchar(1000) DEFAULT NULL,
  `announcement_date` date DEFAULT NULL,
  `Intermediary_company_handling` int(11) DEFAULT NULL,
  `extent` int(11) DEFAULT NULL,
  `supply_restriction_detail` varchar(2) DEFAULT NULL,
  `pre_order_limit` date DEFAULT NULL,
  `award` varchar(30) DEFAULT NULL,
  `readers_write` int(11) DEFAULT NULL,
  `readers_write_page` int(11) DEFAULT NULL,
  `production_notes_item` varchar(2) DEFAULT NULL,
  `cd_dvd` int(11) DEFAULT NULL,
  `bond_name` varchar(10) DEFAULT NULL,
  `comments` varchar(200) DEFAULT NULL,
  `band_contents` varchar(100) DEFAULT NULL,
  `competition` varchar(100) DEFAULT NULL,
  `separate_material` varchar(100) DEFAULT NULL,
  `childrens_book_genre` varchar(2) DEFAULT NULL,
  `font_size` int(11) DEFAULT NULL,
  `ruby` int(11) DEFAULT NULL,
  `percentage_of_manga` int(11) DEFAULT NULL,
  `special_binding` varchar(100) DEFAULT NULL,
  `trick` varchar(100) DEFAULT NULL,
  `other_notices` int(11) DEFAULT NULL,
  `number_of_first_edition` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `return_deadline` date DEFAULT NULL,
  `band` int(11) DEFAULT NULL,
  `cover` int(11) DEFAULT NULL,
  `binding_place` varchar(100) DEFAULT NULL,
  `number_of_cohesions` int(11) DEFAULT NULL,
  `author_data` text,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`book_no`),
  UNIQUE KEY `isbn_UNIQUE` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jpro_data_yesterday`;
CREATE TABLE `jpro_data_yesterday` (
  `book_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `notification_type` varchar(100) DEFAULT NULL,
  `jan_code` varchar(13) DEFAULT NULL,
  `magazine_code` varchar(7) DEFAULT NULL,
  `publication_form` varchar(2) DEFAULT NULL,
  `c_code` varchar(4) DEFAULT NULL,
  `subject_code` varchar(2) DEFAULT NULL,
  `book_name` varchar(300) DEFAULT NULL,
  `volume` varchar(20) DEFAULT NULL,
  `kana` varchar(300) DEFAULT NULL,
  `monthly_issue` varchar(20) DEFAULT NULL,
  `sub_name` varchar(300) DEFAULT NULL,
  `sub_kana` varchar(300) DEFAULT NULL,
  `imprint_name` varchar(300) DEFAULT NULL,
  `imprint_collationkey` varchar(300) DEFAULT NULL,
  `series_name` varchar(300) DEFAULT NULL,
  `series_kana` varchar(300) DEFAULT NULL,
  `completion` int(11) DEFAULT NULL,
  `each_volume_name` varchar(300) DEFAULT NULL,
  `each_volume_kana` varchar(300) DEFAULT NULL,
  `extent_value` int(11) DEFAULT NULL,
  `unpriced_item_type` varchar(2) DEFAULT NULL,
  `publisher_transaction_code` varchar(4) DEFAULT NULL,
  `publisher_name` varchar(100) DEFAULT NULL,
  `publisher_prefix` varchar(100) DEFAULT NULL,
  `selling_transaction_code` varchar(4) DEFAULT NULL,
  `selling_name` varchar(100) DEFAULT NULL,
  `selling_prefix` varchar(100) DEFAULT NULL,
  `language` varchar(30) DEFAULT NULL,
  `book_size_no` varchar(4) DEFAULT NULL,
  `measure_height` int(11) DEFAULT NULL,
  `measure_width` int(11) DEFAULT NULL,
  `measure_thickness` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `price_amount` int(11) DEFAULT NULL,
  `price_effective_until` date DEFAULT NULL,
  `reselling` int(11) DEFAULT NULL,
  `reselling_date` date DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `on_sale_date` date DEFAULT NULL,
  `Issued_date` date DEFAULT NULL,
  `audience_code_value` varchar(2) DEFAULT NULL,
  `audience_description` varchar(2) DEFAULT NULL,
  `outline` text,
  `long_description` text,
  `recent_publication` text,
  `library_selection_content` varchar(105) DEFAULT NULL,
  `explain` text,
  `keyword` varchar(250) DEFAULT NULL,
  `product_form_description` varchar(1000) DEFAULT NULL,
  `announcement_date` date DEFAULT NULL,
  `Intermediary_company_handling` int(11) DEFAULT NULL,
  `extent` int(11) DEFAULT NULL,
  `supply_restriction_detail` varchar(2) DEFAULT NULL,
  `pre_order_limit` date DEFAULT NULL,
  `award` varchar(30) DEFAULT NULL,
  `readers_write` int(11) DEFAULT NULL,
  `readers_write_page` int(11) DEFAULT NULL,
  `production_notes_item` varchar(2) DEFAULT NULL,
  `cd_dvd` int(11) DEFAULT NULL,
  `bond_name` varchar(10) DEFAULT NULL,
  `comments` varchar(200) DEFAULT NULL,
  `band_contents` varchar(100) DEFAULT NULL,
  `competition` varchar(100) DEFAULT NULL,
  `separate_material` varchar(100) DEFAULT NULL,
  `childrens_book_genre` varchar(2) DEFAULT NULL,
  `font_size` int(11) DEFAULT NULL,
  `ruby` int(11) DEFAULT NULL,
  `percentage_of_manga` int(11) DEFAULT NULL,
  `special_binding` varchar(100) DEFAULT NULL,
  `trick` varchar(100) DEFAULT NULL,
  `other_notices` int(11) DEFAULT NULL,
  `number_of_first_edition` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `return_deadline` date DEFAULT NULL,
  `band` int(11) DEFAULT NULL,
  `cover` int(11) DEFAULT NULL,
  `binding_place` varchar(100) DEFAULT NULL,
  `number_of_cohesions` int(11) DEFAULT NULL,
  `author_data` text,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`book_no`),
  UNIQUE KEY `isbn_UNIQUE` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `label`;
CREATE TABLE `label` (
  `label_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `url` text,
  `publisher_no` int(11) DEFAULT NULL,
  `display` int(11) DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`label_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `linkage`;
CREATE TABLE `linkage` (
  `linkage_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `id` text NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`linkage_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `linkage_commissioner`;
CREATE TABLE `linkage_commissioner` (
  `linkage_commissioner_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `set_date` datetime DEFAULT NULL,
  `process_date` datetime DEFAULT NULL,
  `data_type_2` text,
  `send_type_1` text,
  `source_type_1` text,
  `source_code_1` text,
  `to_type_1` text,
  `to_code_1` text,
  `c_stamp_1` text,
  `record_type_1` text,
  `cancell_type_1` text,
  `category_code_1` text,
  `trade_code_1` text,
  `trade_code_branch_1` text,
  `issuer_1` text,
  `issuer_kana_1` text,
  `publisher_2` text,
  `publisher_kana_2` text,
  `handling_company_1` text,
  `handling_type_1` text,
  `series_1` text,
  `series_kana_1` text,
  `series_volume_1` text,
  `sub_series_1` text,
  `sub_series_kana_1` text,
  `sub_series_volume_1` text,
  `total_volume_1` text,
  `total_other_volume_1` text,
  `distribution_count_1` text,
  `name_1` text,
  `kana_1` text,
  `volume_2` text,
  `sub_1` text,
  `sub_kana_1` text,
  `sub_volume_1` text,
  `end_1` text,
  `present_volume_1` text,
  `author_1` text,
  `author_kana_1` text,
  `author_type_1` text,
  `author_2` text,
  `author_kana_2` text,
  `author_type_2` text,
  `author_3` text,
  `author_kana_3` text,
  `author_type_3` text,
  `content_1` text,
  `content_2` text,
  `preliminary_5` text,
  `book_size_2` text,
  `book_size_3` text,
  `page_1` text,
  `bound_1` text,
  `release_date_1` text,
  `return_date_1` text,
  `notation_price_1` text,
  `price_1` text,
  `price_tax_1` text,
  `price_special_1` text,
  `price_special_tax_1` text,
  `price_special_policy_1` text,
  `distribution_type_1` text,
  `distribut_1` text,
  `isbn_1` text,
  `category_1` text,
  `magazine_code_2` text,
  `magazine_code_1` text,
  `adult_1` text,
  `pre_order_1` text,
  `order_status_1` text,
  `circulation_1` text,
  `fix_1` text,
  `typist_1` text,
  `typist_tel_1` text,
  `type_date_1` text,
  `edit_time_stamp_1` text,
  `win_info_1` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`linkage_commissioner_no`),
  UNIQUE KEY `book_no` (`book_no`),
  KEY `book_no_2` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `linkage_jbpa`;
CREATE TABLE `linkage_jbpa` (
  `opus_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `set_date` datetime DEFAULT NULL,
  `process_date` datetime DEFAULT NULL,
  `data_type_1` text,
  `isbn_1` text,
  `category_1` text,
  `name_1` text,
  `kana_1` text,
  `preliminary_1` text,
  `sub_1` text,
  `version_1` text,
  `preliminary_2` text,
  `series_1` text,
  `series_kana_1` text,
  `preliminary_3` text,
  `author_1` text,
  `author_type_1` text,
  `author_kana_1` text,
  `author_2` text,
  `author_type_2` text,
  `author_kana_2` text,
  `author_3` text,
  `author_type_3` text,
  `author_kana_3` text,
  `book_date_1` text,
  `release_date_1` text,
  `book_size_2` text,
  `page_1` text,
  `set_code_1` text,
  `price_1` text,
  `price_change_date_1` text,
  `price_special_1` text,
  `price_special_policy_1` text,
  `preliminary_4` text,
  `preliminary_5` text,
  `publisher_1` text,
  `publisher_2` text,
  `preliminary_6` text,
  `out_status_1` text,
  `out_date_1` text,
  `preliminary_7` text,
  `trade_code_1` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`opus_no`),
  UNIQUE KEY `book_no` (`book_no`),
  KEY `book_no_2` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `linkage_shared_field`;
CREATE TABLE `linkage_shared_field` (
  `linkage_shared_field_no` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` varchar(191) NOT NULL,
  `book_no` int(11) NOT NULL,
  `table_id` text NOT NULL,
  PRIMARY KEY (`linkage_shared_field_no`),
  UNIQUE KEY `field_id` (`field_id`,`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `linkage_trc`;
CREATE TABLE `linkage_trc` (
  `linkage_trc_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `set_date` datetime DEFAULT NULL,
  `process_date` datetime DEFAULT NULL,
  `name_1` text,
  `kana_1` text,
  `sub_1` text,
  `series_1` text,
  `volume_1` text,
  `author_1` text,
  `author_2` text,
  `author_kana_1` text,
  `author_kana_2` text,
  `author_note_1` text,
  `author_note_2` text,
  `isbn_1` text,
  `price_1` text,
  `genre_1` text,
  `genre_2` text,
  `book_size_1` text,
  `page_1` text,
  `content_1` text,
  `version_release_1` text,
  `price_special_1` text,
  `price_special_policy_1` text,
  `release_date_1` text,
  `magazine_code_1` text,
  `publisher_2` text,
  `publisher_kana_2` text,
  `issuer_1` text,
  `issuer_kana_1` text,
  `sub_kana_1` text,
  `circulation_1` text,
  `typist_1` text,
  `typist_tel_1` text,
  `type_date_1` text,
  `explain_1` text,
  `distribution_type_1` text,
  `order_status_1` text,
  `target_1` text,
  `rubi_status_1` text,
  `note_1` text,
  `win_info_1` text,
  `reader_page_status_1` text,
  `reader_page_1` text,
  `unaccompanied_status_1` text,
  `by_format_1` text,
  `by_obi_1` text,
  `representative_editor_1` text,
  `representative_comment_1` text,
  `conflicts_1` text,
  `appendices_status_1` text,
  `appendices_type_1` text,
  `appendices_other_1` text,
  `appendices_loan_1` text,
  `appendix_1` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`linkage_trc_no`),
  UNIQUE KEY `book_no` (`book_no`),
  KEY `book_no_2` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `netshop`;
CREATE TABLE `netshop` (
  `netshop_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `id` text NOT NULL,
  `url` text NOT NULL,
  `url_mobile` text,
  `textsearch_url` text,
  `charset` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`netshop_no`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `news_category_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` mediumtext NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `public_date` datetime DEFAULT NULL,
  `navi_display` int(11) NOT NULL DEFAULT '1',
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`news_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `news_category_no` (`news_category_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `news_category`;
CREATE TABLE `news_category` (
  `news_category_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `news_fix_category_no` int(11) DEFAULT NULL,
  `name` text,
  `display` int(11) DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`news_category_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `news_fix_category_no` (`news_fix_category_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `news_fix_category`;
CREATE TABLE `news_fix_category` (
  `news_fix_category_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`news_fix_category_no`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `news_news_category`;
CREATE TABLE `news_news_category` (
  `news_news_category_no` int(11) NOT NULL AUTO_INCREMENT,
  `news_no` int(11) NOT NULL,
  `news_category_no` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`news_news_category_no`),
  UNIQUE KEY `news_no_news_category_no` (`news_no`,`news_category_no`),
  KEY `news_no` (`news_no`),
  KEY `news_category_no` (`news_category_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `news_relate`;
CREATE TABLE `news_relate` (
  `news_relate_no` int(11) NOT NULL AUTO_INCREMENT,
  `news_no` int(11) NOT NULL,
  `book_no` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`news_relate_no`),
  KEY `news_no` (`news_no`),
  KEY `book_no` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `opus`;
CREATE TABLE `opus` (
  `opus_no` int(11) NOT NULL AUTO_INCREMENT,
  `book_no` int(11) DEFAULT NULL,
  `author_no` int(11) DEFAULT NULL,
  `author_type_no` int(11) DEFAULT NULL,
  `author_type_other` text,
  `contributor_role` varchar(191) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`opus_no`),
  KEY `book_no` (`book_no`),
  KEY `author_no` (`author_no`),
  KEY `author_type_no` (`author_type_no`),
  KEY `order_no` (`c_stamp`,`opus_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `payment_option`;
CREATE TABLE `payment_option` (
  `payment_option_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_option_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `publisher`;
CREATE TABLE `publisher` (
  `publisher_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `kana` text,
  `id` text NOT NULL,
  `pass` text NOT NULL,
  `design` text,
  `zipcode` text,
  `address` text,
  `tel` text,
  `fax` text,
  `logo` text,
  `person_name` text,
  `person_mail` text,
  `linkage_person_name` text,
  `linkage_person_mail` text,
  `url` text,
  `copyright` text,
  `transaction_code` text,
  `catchphrase` text,
  `description` text,
  `amazon_associates_id` text,
  `rakuten_affiliate_id` text,
  `seven_and_y_url` text,
  `erupakabooks_url` text,
  `google_analytics_tag` text,
  `cart` int(11) DEFAULT NULL,
  `recommend_type` int(11) DEFAULT NULL,
  `bookservice_no` text,
  `admin_status` int(11) DEFAULT NULL,
  `contact_mail` text,
  `cart_mail` text,
  `yondemill_userid` text,
  `yondemill_auth_token` text,
  `yondemill_book_sales` int(11) DEFAULT NULL,
  `banner_width` int(11) DEFAULT NULL,
  `banner_height` int(11) DEFAULT NULL,
  `banner_big_status` int(11) DEFAULT NULL,
  `banner_big_width` int(11) DEFAULT NULL,
  `banner_big_height` int(11) DEFAULT NULL,
  `banner_big_limit` int(11) DEFAULT NULL,
  `great_img_status` int(11) DEFAULT NULL,
  `freeitem` text,
  `ga_account` text,
  `ga_password` text,
  `ga_report` bigint(20) DEFAULT NULL,
  `gapi_version` text NOT NULL,
  `from_person_unit` text,
  `jpo` int(11) NOT NULL DEFAULT '0',
  `publisher_prefix` varchar(7) DEFAULT NULL,
  `publisher_prefix_next` text,
  `role` int(11) NOT NULL DEFAULT '0',
  `book_notice_mail` text,
  `smp` int(11) NOT NULL DEFAULT '0',
  `smp_path` text,
  `import_images` int(11) DEFAULT NULL,
  `ebook_store_status` int(11) DEFAULT NULL,
  `news_category_edit` int(1) NOT NULL DEFAULT '0',
  `hondanaec_status` int(1) DEFAULT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `publisher_account`;
CREATE TABLE `publisher_account` (
  `publisher_account_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `role_no` int(11) NOT NULL,
  `name` text,
  `id` text NOT NULL,
  `password` text NOT NULL,
  `is_default` int(11) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`publisher_account_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `publisher_ebookstores`;
CREATE TABLE `publisher_ebookstores` (
  `publisher_ebookstores_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `ebookstores_no` int(11) NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `display_order` int(11) NOT NULL DEFAULT '1',
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`publisher_ebookstores_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `ebookstores_no` (`ebookstores_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `publisher_netshop`;
CREATE TABLE `publisher_netshop` (
  `publisher_netshop_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `netshop_no` int(11) NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `display_order` int(11) NOT NULL DEFAULT '1',
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`publisher_netshop_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `netshop_no` (`netshop_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `publisher_payment`;
CREATE TABLE `publisher_payment` (
  `publisher_payment_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) NOT NULL,
  `payment_option_no` int(11) NOT NULL,
  `public_status` int(11) NOT NULL DEFAULT '1',
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`publisher_payment_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `payment_option_no` (`payment_option_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) DEFAULT NULL,
  `name` text,
  `is_default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_no`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `role_access`;
CREATE TABLE `role_access` (
  `role_no` int(11) NOT NULL,
  `access_no` int(11) NOT NULL,
  UNIQUE KEY `role_no` (`role_no`,`access_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `role_access_book`;
CREATE TABLE `role_access_book` (
  `role_no` int(11) NOT NULL,
  `access_book_no` int(11) NOT NULL,
  UNIQUE KEY `role_no` (`role_no`,`access_book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `series`;
CREATE TABLE `series` (
  `series_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `kana` text,
  `publisher_no` int(11) DEFAULT NULL,
  `display` int(11) DEFAULT '1',
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`series_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `sites_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) DEFAULT NULL,
  `name` text,
  `keyid` int(11) DEFAULT NULL,
  `url` text,
  `sort` int(11) NOT NULL,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`sites_no`),
  KEY `publisher_no` (`publisher_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `special_site_link`;
CREATE TABLE `special_site_link` (
  `special_site_link_no` int(11) NOT NULL AUTO_INCREMENT,
  `publisher_no` int(11) DEFAULT NULL,
  `book_no` int(11) NOT NULL,
  `imagefile` text,
  `name` text,
  `url` text,
  `sort` int(11) NOT NULL,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`special_site_link_no`),
  KEY `publisher_no` (`publisher_no`),
  KEY `book_no` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stock_status`;
CREATE TABLE `stock_status` (
  `stock_status_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `note` text,
  `c_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `u_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `d_stamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_status_no`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `yondemill_book`;
CREATE TABLE `yondemill_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yondemill_id` int(11) NOT NULL,
  `browse_url` text COLLATE utf8_unicode_ci NOT NULL,
  `modified` datetime NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `yondemill_id` (`yondemill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `access` (`access_no`, `name`, `url`, `is_depth`, `allow`) VALUES
(6, 'システム設定管理機能へのアクセスを不許可', '/admin/publisher/system/', 0, NULL),
(7, 'アカウント管理機能へのアクセスを不許可', '/admin/publisher/account/', 0, NULL),
(11, '書誌管理 ジャンル登録へのアクセスを不許可', '/admin/publisher/book/genre/new/', 0, NULL),
(12, '書誌管理 シリーズ登録へのアクセスを不許可', '/admin/publisher/book/series/new/', 0, NULL),
(13, '書誌管理 ジャンル編集へのアクセスを不許可', '/admin/publisher/book/genre/edit/', 0, NULL),
(14, '書誌管理 シリーズ編集へのアクセスを不許可', '/admin/publisher/book/series/edit/', 0, NULL),
(15, '書誌管理 レーベル登録へのアクセスを不許可', '/admin/publisher/book/label/new/', 0, NULL),
(16, '書誌管理 レーベル編集へのアクセスを不許可', '/admin/publisher/book/label/edit/', 0, NULL),
(17, '書誌管理 レーベル削除へのアクセスを不許可', '/admin/publisher/book/label/delete/', 0, NULL),
(18, '書籍管理 削除へのアクセスを不許可', '/admin/publisher/book/delete/', 0, NULL),
(19, '著者情報管理 削除へのアクセスを不許可', '/admin/publisher/author/delete/', 0, NULL),
(20, '書誌管理 ジャンル削除へのアクセスを不許可', '/admin/publisher/book/genre/delete/', 0, NULL),
(21, '書誌管理 シリーズ削除へのアクセスを不許可', '/admin/publisher/book/series/delete/', 0, NULL),
(22, '著者情報管理 登録へのアクセスを不許可', '/admin/publisher/author/new/', 0, NULL),
(23, '著者情報管理 編集へのアクセスを不許可', '/admin/publisher/author/edit/', 0, NULL),
(24, 'ジャンルポップアップ 登録へのアクセスを不許可', '/admin/publisher/book/genre_popup/new/', 0, NULL),
(25, 'ジャンルポップアップ 編集へのアクセスを不許可', '/admin/publisher/book/genre_popup/edit/', 0, NULL),
(26, 'シリーズポップアップ 登録へのアクセスを不許可', '/admin/publisher/book/series_popup/new/', 0, NULL),
(27, 'シリーズポップアップ 編集へのアクセスを不許可', '/admin/publisher/book/series_popup/edit/', 0, NULL),
(28, 'レーベルポップアップ 登録へのアクセスを不許可', '/admin/publisher/book/label_popup/new/', 0, NULL),
(29, 'レーベルポップアップ 編集へのアクセスを不許可', '/admin/publisher/book/label_popup/edit/', 0, NULL),
(30, '著者ポップアップ 登録へのアクセスを不許可', '/admin/publisher/author/author_popup/new/', 0, NULL),
(31, '著者ポップアップ 編集へのアクセスを不許可', '/admin/publisher/author/author_popup/edit/', 0, NULL),
(32, 'ジャンルポップアップ 削除へのアクセスを不許可', '/admin/publisher/book/genre_popup/delete/', 0, NULL),
(33, 'シリーズポップアップ 削除へのアクセスを不許可', '/admin/publisher/book/series_popup/delete/', 0, NULL),
(34, 'レーベルポップアップ 削除へのアクセスを不許可', '/admin/publisher/book/label_popup/delete/', 0, NULL),
(35, '著者ポップアップ 削除へのアクセスを不許可', '/admin/publisher/author/author_popup/delete/', 0, NULL),
(36, '書籍管理 削除へのアクセスを不許可', '/admin/publisher/book/delete/', 0, NULL);

INSERT INTO `access_book` (`access_book_no`, `name`, `code`, `options`) VALUES
(1, 'ジャンル編集不可', 'Genre', 'set_genre,delete_genre');
INSERT INTO `access_book` (`access_book_no`, `name`, `code`, `options`) VALUES
(2, 'シリーズ編集不可', 'Series', 'set_series,delete_series');
INSERT INTO `access_book` (`access_book_no`, `name`, `code`, `options`) VALUES
(3, '書影編集不可', 'Image', NULL);
INSERT INTO `access_book` (`access_book_no`, `name`, `code`, `options`) VALUES
(4, '発売日関連編集不可', 'ReleaseDate', NULL),
(5, '版編集不可', 'Version', NULL),
(6, 'キーワード編集不可', 'Keyword', NULL),
(7, '公開設定編集不可', 'PublishDate', 'display,display_none'),
(8, '新刊関連編集不可', 'New', 'new_status,new_status_none,next_book,next_book_none'),
(9, 'おすすめ関連編集不可', 'Recommend', 'recommend_status,recommend_status_none'),
(10, 'カート関連編集不可', 'Cart', 'set_stock_status,delete_stock_status,cart,cart_none'),
(11, '立ち読みファイル編集不可', 'ActiBook', NULL),
(12, 'データ連携関連編集不可', 'Linkage', NULL),
(13, 'JPO 非表示', 'Jpo', NULL),
(14, '電子書籍 非表示', 'Ebook', NULL),
(15, '本が好き！ 非表示', 'Honzuki', NULL),
(16, 'フリー項目3 非表示', 'Free3', NULL),
(17, 'フリー項目4 非表示', 'Free4', NULL),
(18, 'フリー項目5 非表示', 'Free5', NULL),
(19, 'フリー項目6 非表示', 'Free6', NULL),
(20, 'フリー項目7 非表示', 'Free7', NULL),
(21, 'フリー項目8 非表示', 'Free8', NULL),
(22, 'YONDEMILL 非表示', 'Yondemill', NULL),
(23, '書籍データ削除', 'Delete', 'delete');

INSERT INTO `author_type` (`author_type_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, '著', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL);
INSERT INTO `author_type` (`author_type_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '訳', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL);
INSERT INTO `author_type` (`author_type_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, '作', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL);
INSERT INTO `author_type` (`author_type_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, '原作', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(5, '原案', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(6, '編', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(7, '編著', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(8, '編訳', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(9, '編注', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(10, '監', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(11, '監訳', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(12, '文', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(13, '絵', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(14, '画', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL),
(15, '写真', NULL, '2008-07-15 10:29:27', '2008-07-15 10:29:27', NULL);

INSERT INTO `book_field` (`book_field_no`, `name`, `category`) VALUES
(1, '書名', '概要');
INSERT INTO `book_field` (`book_field_no`, `name`, `category`) VALUES
(2, '書名カナ', '概要');
INSERT INTO `book_field` (`book_field_no`, `name`, `category`) VALUES
(3, '巻次', '概要');
INSERT INTO `book_field` (`book_field_no`, `name`, `category`) VALUES
(4, 'サブタイトル', '概要'),
(5, 'サブタイトルカナ', '概要'),
(6, 'ジャンル', '概要'),
(7, 'シリーズ', '概要'),
(8, '書影', '詳細'),
(9, '著者名', '詳細'),
(10, '著者区分', '詳細'),
(11, 'ISBN', '詳細'),
(12, 'Cコード', '詳細'),
(13, '雑誌コード', '詳細'),
(14, '出版年月日', '詳細'),
(15, '書店発売日', '詳細'),
(16, '版', '詳細'),
(17, '判型', '詳細'),
(18, '判型（実寸）', '詳細'),
(19, 'ページ数', '詳細'),
(20, '本体価格', '詳細'),
(21, '概要（長文）', '内容紹介'),
(22, '概要（短文）', '内容紹介'),
(23, '目次', '内容紹介'),
(24, '内容説明', '内容紹介'),
(25, 'キーワード', '設定'),
(26, '公開の状態', '設定'),
(27, '公開日指定', '設定'),
(28, '新刊設定', '設定'),
(29, 'これから出る本設定', '設定'),
(30, 'おすすめ設定', '設定'),
(31, 'おすすめ表示順', '設定'),
(32, 'おすすめ紹介文', '設定'),
(33, '在庫ステータス', '設定'),
(34, '関連書誌', '設定'),
(35, '最新刊情報テキスト', '設定'),
(36, 'ActiBookファイル', '立ち読みファイルアップロード'),
(37, 'レーベル', '概要');

INSERT INTO `book_format` (`id`, `name`) VALUES
(1, '単行本');
INSERT INTO `book_format` (`id`, `name`) VALUES
(2, '文庫');
INSERT INTO `book_format` (`id`, `name`) VALUES
(3, '新書');
INSERT INTO `book_format` (`id`, `name`) VALUES
(4, 'コミック'),
(5, '電子書籍'),
(6, 'その他');


INSERT INTO `book_size` (`book_size_no`, `name`, `order`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, '4-6', 1, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL);
INSERT INTO `book_size` (`book_size_no`, `name`, `order`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '4-6変', 2, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL);
INSERT INTO `book_size` (`book_size_no`, `name`, `order`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, 'B6', 3, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL);
INSERT INTO `book_size` (`book_size_no`, `name`, `order`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, 'B6変', 4, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(5, 'A5', 5, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(6, 'A5変', 6, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(7, '文庫', 7, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(8, '新書', 8, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(9, 'B5', 9, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(10, 'B5変', 10, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(11, 'A4', 11, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(12, 'A4変', 12, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(13, 'A6', 13, NULL, '2008-07-02 12:53:21', '2013-09-12 03:04:52', NULL),
(14, 'A6変', 14, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(15, 'AB', 15, NULL, '2008-07-02 12:53:21', '2013-05-20 06:28:38', NULL),
(16, 'B4', 17, NULL, '2008-07-02 12:53:21', '2013-05-20 06:29:49', NULL),
(17, '菊判', 18, NULL, '2008-07-02 12:53:21', '2013-05-20 06:29:49', NULL),
(18, '菊倍判', 19, NULL, '2008-07-02 12:53:21', '2013-05-20 06:29:49', NULL),
(19, '菊判変', 20, NULL, '2008-07-02 12:53:21', '2013-05-20 06:29:49', NULL),
(20, 'その他・規格外', 21, NULL, '2008-07-02 12:53:21', '2013-05-20 06:29:49', NULL),
(21, 'B7', 16, NULL, '2013-05-19 15:00:00', '2013-05-20 06:31:01', NULL);

INSERT INTO `company_category` (`company_category_no`, `publisher_no`, `name`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 2, '会社情報カテゴリ', 0, 1, 2, 0, NULL, '2021-06-24 09:59:42', '2021-06-24 10:21:13', NULL);


INSERT INTO `company_fix_category` (`company_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, '会社情報カテゴリ', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `company_fix_category` (`company_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '会社概要', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `company_fix_category` (`company_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, 'アクセスマップ', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `company_fix_category` (`company_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, '採用情報', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(5, '沿革', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(6, 'ご注文方法', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(7, '個人情報保護への取り組み', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(8, 'サイトマップ', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(9, 'サイトポリシー', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);

INSERT INTO `ebookstores` (`id`, `name`, `url`, `search_url`, `charset`, `created_at`, `updated_at`) VALUES
(1, 'Amazon Kindle', 'http://www.amazon.co.jp/', 'http://www.amazon.co.jp/s/?url=search-alias%3Ddigital-text&field-keywords=#keyword#', 'utf8', '2013-03-26 10:47:34', '2013-03-26 10:47:34');
INSERT INTO `ebookstores` (`id`, `name`, `url`, `search_url`, `charset`, `created_at`, `updated_at`) VALUES
(2, '楽天Kobo', 'http://rakuten.kobobooks.com/', 'http://search.books.rakuten.co.jp/bksearch/nm?g=101&sitem=#keyword#', 'euc', '2013-03-26 10:47:34', '2013-03-26 10:47:34');
INSERT INTO `ebookstores` (`id`, `name`, `url`, `search_url`, `charset`, `created_at`, `updated_at`) VALUES
(3, 'eBookJapan', 'http://www.ebookjapan.jp/ebj/', 'http://www.ebookjapan.jp/ebj/search_book/?q=#keyword#', 'utf8', '2013-03-26 10:47:34', '2013-03-26 10:47:34');
INSERT INTO `ebookstores` (`id`, `name`, `url`, `search_url`, `charset`, `created_at`, `updated_at`) VALUES
(4, 'Yahoo!ブックストア', 'http://bookstore.yahoo.co.jp/', 'http://bookstore.yahoo.co.jp/search?keyword=#keyword#', 'utf8', '2013-03-26 10:47:34', '2013-03-26 10:47:34'),
(5, '電子書店パピレス', 'http://www.papy.co.jp/', 'http://www.papy.co.jp/sc/list/search?word=#keyword#', 'euc', '2013-03-26 10:47:34', '2013-03-26 10:47:34'),
(6, 'BinB store', 'http://binb-store.com/', 'http://binb-store.com/index.php?main_page=addon&module=voyager_store_asp%2Fkeyword_product_list&keyword=#keyword#', 'utf8', '2013-03-26 10:47:34', '2013-03-26 10:47:34'),
(7, 'Google Play ブックス', 'http://books.google.co.jp/books', 'http://books.google.co.jp/books?q=#keyword#', 'utf8', '2013-04-16 03:25:47', '2013-04-16 03:25:47'),
(8, '紀伊國屋書店', 'http://www.kinokuniya.co.jp/', 'https://www.kinokuniya.co.jp/disp/CSfDispListPage_001.jsp?qs=true&ptk=03&q=#keyword#', 'utf8', '2014-02-27 10:30:00', '2014-02-27 10:30:00'),
(9, 'BookLive!', 'http://booklive.jp/', 'http://booklive.jp/search/keyword?keyword=#keyword#', 'utf8', '2014-06-13 10:43:00', '2014-06-13 10:43:00'),
(10, 'honto 電子書籍ストア', 'http://honto.jp/', 'http://honto.jp/ebook/search_022_10#keyword#.html', 'utf8', '2016-06-17 10:49:26', '2016-06-17 10:49:26'),
(11, 'セブンネット', 'https://7net.omni7.jp/', 'https://7net.omni7.jp/search/?keyword=#keyword#&siteCateCode=050001', 'utf8', NULL, NULL),
(12, 'iBooks', 'https://www.apple.com/jp/ibooks/', 'https://www.apple.com/jp/ibooks/', 'utf8', NULL, NULL),
(13, 'Google Play Books', 'https://play.google.com/', 'https://play.google.com/store/search?q=#keyword#&c=books', 'utf8', NULL, NULL),
(14, 'BOOK WALKER', 'https://bookwalker.jp/', 'https://bookwalker.jp/search/?qcat=&word=#keyword#', 'utf8', NULL, NULL),
(15, 'ヨドバシ.com', 'https://www.yodobashi.com/', 'https://www.yodobashi.com/category/151007/?word=#keyword#', 'utf8', NULL, NULL);


INSERT INTO `label` (`label_no`, `name`, `url`, `publisher_no`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'レーベル', NULL, 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL);

INSERT INTO `series` (`series_no`, `name`, `publisher_no`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'シリーズ', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL);

INSERT INTO `genre` (`genre_no`, `name`, `publisher_no`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'ライトノベル', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(2, '文芸一般', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(3, 'コミック', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(4, '絵本', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(5, '児童書', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(6, '社会一般', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL),
(7, 'その他ジャンル', 2, 0, 1, 2, 0, NULL, '2021-06-24 09:37:40', '2021-06-24 09:37:40', NULL);

INSERT INTO `linkage` (`linkage_no`, `name`, `id`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'TRC', 'trc', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `linkage` (`linkage_no`, `name`, `id`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '書協', 'jbpa', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `linkage` (`linkage_no`, `name`, `id`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, '取次', 'commissioner', NULL, '2008-10-30 09:04:46', '2008-10-30 09:04:46', NULL);


INSERT INTO `netshop` (`netshop_no`, `name`, `id`, `url`, `url_mobile`, `textsearch_url`, `charset`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'Amazon', 'amazon', 'http://www.amazon.co.jp/gp/search/?field-isbn=#isbn#', 'http://www.amazon.co.jp/exec/obidos/ASIN/#asin#/', 'http://www.amazon.co.jp/s/ref=nb_sb_noss_1?url=search-alias%3Daps&field-keywords=#text#', NULL, NULL, '2008-07-02 12:53:21', '2014-12-24 05:50:40', NULL);
INSERT INTO `netshop` (`netshop_no`, `name`, `id`, `url`, `url_mobile`, `textsearch_url`, `charset`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, 'e-hon 全国書店ネットワーク', 'ehon', 'http://www.e-hon.ne.jp/bec/SA/Forward?isbn=#isbn#&mode=kodawari&button=btnKodawari', 'http://m.e-hon.ne.jp/', 'http://www.e-hon.ne.jp/bec/SA/AllForward?spKeyword=#text#&mode=speed&&button=btnSpeed&refHpStenhnbCode=AAAA', 'SJIS', NULL, '2008-07-02 12:53:21', '2012-08-27 05:33:39', NULL);
INSERT INTO `netshop` (`netshop_no`, `name`, `id`, `url`, `url_mobile`, `textsearch_url`, `charset`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, 'boople.com', 'boople', 'http://www.boople.com/bst/BPdispatch?title=&title_andor=0&title_title=1&title_subtitle=1&title_series=1&title_original=1&publisher=&author=&author_list=0&free=&isbn_cd=#isbn#&vague_search=1&date_yy_from=&date_mm_from=&date_yy_to=&date_mm_to=&price_from=&price_to=&stamp_style=0&list_kensu=20&pub_stat=2&submit.x=0&submit.y=0', NULL, NULL, NULL, NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `netshop` (`netshop_no`, `name`, `id`, `url`, `url_mobile`, `textsearch_url`, `charset`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, 'セブンネットショッピング', '7andy', 'http://www.7netshopping.jp/books/detail/-/isbn/#isbn#/ ', NULL, 'http://www.7netshopping.jp/all/search_result/?kword_in=#text#', NULL, NULL, '2008-07-02 12:53:21', '2014-12-24 05:50:40', NULL),
(5, '本やタウン', 'honya', 'http://www.honya-town.co.jp/hst/HTdispatch?isbn_cd=#isbn#', NULL, NULL, NULL, NULL, '2008-07-02 12:53:21', '2009-01-05 07:14:57', NULL),
(6, '楽天ブックス', 'rakuten', 'http://esearch.rakuten.co.jp/rms/sd/esearch/vc?sv=30&v=2&oid=000&f=A&g=001&p=0&s=0&e=0&sitem=#isbn#', 'http://m.rakuten.co.jp/', 'http://search.books.rakuten.co.jp/bksearch/nm?sitem=#text#', 'EUC-JP', NULL, '2008-07-02 12:53:21', '2012-07-04 02:35:55', NULL),
(7, 'MARUZEN & JUNKUDO', 'junkudo', 'http://honto.jp/cp/junkudo?cid=ip_ec_alsr', 'http://honto.jp/cp/junkudo?cid=ip_ec_alsr', 'http://honto.jp/cp/junkudo?cid=ip_ec_alsr', NULL, NULL, '2008-07-02 12:53:21', '2016-04-12 05:24:48', NULL),
(8, '紀伊國屋書店', 'kinokuniya', 'http://www.kinokuniya.co.jp/f/dsg-01-#isbn#', NULL, 'https://www.kinokuniya.co.jp/disp/CSfDispListPage_001.jsp?qs=true&ptk=07&q=#text#', '', NULL, '2008-07-02 12:53:21', '2015-04-25 10:06:37', NULL),
(9, 'TSUTAYA online', 'tsutaya', 'http://shop.tsutaya.co.jp/book/product/#isbn#/', 'http://tsutaya.jp/', 'http://shop.tsutaya.co.jp/search_result.html?ecCategory=05&searchKeyword=#text#', NULL, NULL, '2008-07-02 12:53:21', '2012-07-04 02:37:00', NULL),
(10, 'honto', 'honto', 'http://honto.jp/isbn/#isbn#', '', 'http://honto.jp/netstore/search_10#text#.html', NULL, NULL, '2008-07-02 12:53:21', '2014-12-24 05:50:40', NULL),
(11, 'ブックサービス', 'bookservice', 'http://www.bookservice.jp/bs/Item/#isbn#', NULL, 'http://www.bookservice.jp/search/SearchItem?CID=-1&FREE_WORD=#text#', NULL, NULL, '2008-07-02 12:53:21', '2012-07-12 07:10:53', NULL),
(12, 'BOOK SHOP 小学館', 'bookshopps', 'http://www.bookshop-ps.com/bsp/bsp_detail?isbn=#isbn#', NULL, 'http://www.bookshop-ps.com/bsp/bsp_search?tp=s&ky=#text#', NULL, NULL, '2008-10-21 08:33:05', '2012-07-04 02:37:57', NULL),
(13, 'Honya Club.com', 'honyaclub', 'http://www.honyaclub.com/shop/affiliate/itemlist.aspx?isbn=#isbn#', 'http://www.honyaclub.com/m/goods/search.aspx?isbn=#isbn#&cat_p=00&search_detail=%8C%9F%8D%F5', 'http://www.honyaclub.com/shop/goods/search.aspx?cat_p=&search=%8C%9F%8D%F5&keyw=#text#', 'SJIS', NULL, '2011-08-26 01:55:10', '2014-12-24 05:50:41', NULL),
(14, 'HMV&BOOKS online', 'erupakabooks', 'http://www.hmv.co.jp/search/searchresults.asp?isbn=#isbn#', NULL, 'http://www.hmv.co.jp/search/searchresults/?category=LBOOKS&keyword=#text#', 'SJIS', NULL, '2012-09-21 06:32:08', '2018-03-09 02:40:34', NULL),
(15, 'BOOKFAN by eBookJapan', 'booxstore', 'http://boox.jp/index.php?module=ecitemdtl&action=pdetail&mtype=partnerPub&it=BK&cd=#isbn#', 'http://boox.jp/index.php?module=ecitemdtl&action=pdetail&mtype=partnerPub&it=BK&cd=#isbn#', 'http://boox.jp/index.php?module=ecsearch&action=plist&it=ALL&q=#text#', NULL, NULL, '2021-06-24 09:37:40', '2015-12-14 14:51:38', NULL),
(16, 'CLUB JAPAN', 'clubjapan', 'http://www.clubjapan.jp/exec/_book/search.php?isbn_cd=#isbn#', NULL, 'http://www.clubjapan.jp/exec/_book/search.php?fCategory=1&fKeyword=#text#&page=header', NULL, NULL, '2021-06-24 09:37:40', '2016-04-15 05:19:06', NULL),
(17, 'ヨドバシ.com', 'yodobashi', 'https://www.yodobashi.com/category/81001/?word=#isbn#\"', NULL, 'https://www.yodobashi.com/category/81001/?word=#text#', NULL, NULL, '2021-06-24 09:37:40', '2018-06-22 04:27:24', NULL);

INSERT INTO `news` (`news_no`, `publisher_no`, `news_category_no`, `name`, `value`, `public_status`, `public_date`, `navi_display`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 2, 2, 'test', 'test', 0, '2021-06-24 09:53:18', 0, NULL, '2021-06-24 10:00:04', '2021-06-24 10:00:04', NULL);


INSERT INTO `news_category` (`news_category_no`, `publisher_no`, `news_fix_category_no`, `name`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 2, 1, 'お知らせカテゴリ', 0, 1, 4, 0, NULL, '2021-06-24 09:59:40', '2021-06-24 09:59:54', NULL);
INSERT INTO `news_category` (`news_category_no`, `publisher_no`, `news_fix_category_no`, `name`, `display`, `lft`, `rgt`, `depth`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, 2, NULL, 'お知らせ', 1, 2, 3, 1, NULL, '2021-06-24 09:59:54', '2021-06-24 09:59:54', NULL),
(3, 2, NULL, '特典情報', 1, 5, 6, 1, NULL, '2021-06-24 09:59:54', '2021-06-24 09:59:54', NULL);


INSERT INTO `news_fix_category` (`news_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'お知らせカテゴリ', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `news_fix_category` (`news_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, 'イベント情報', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `news_fix_category` (`news_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, 'メディアで紹介されました', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `news_fix_category` (`news_fix_category_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, '書店様向け情報', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(5, '注文書', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(6, '店頭POP', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(7, '重版情報', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(8, '正誤表', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(9, '採用情報', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);


INSERT INTO `payment_option` (`payment_option_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, '代金引換', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `payment_option` (`payment_option_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '銀行振込', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `payment_option` (`payment_option_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, '郵便振替', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);

INSERT INTO `publisher` (`publisher_no`, `name`, `kana`, `id`, `pass`, `design`, `zipcode`, `address`, `tel`, `fax`, `logo`, `person_name`, `person_mail`, `linkage_person_name`, `linkage_person_mail`, `url`, `copyright`, `transaction_code`, `catchphrase`, `description`, `amazon_associates_id`, `rakuten_affiliate_id`, `seven_and_y_url`, `erupakabooks_url`, `google_analytics_tag`, `cart`, `recommend_type`, `bookservice_no`, `admin_status`, `contact_mail`, `cart_mail`, `yondemill_userid`, `yondemill_auth_token`, `yondemill_book_sales`, `banner_width`, `banner_height`, `banner_big_status`, `banner_big_width`, `banner_big_height`, `banner_big_limit`, `great_img_status`, `freeitem`, `ga_account`, `ga_password`, `ga_report`, `gapi_version`, `from_person_unit`, `jpo`, `publisher_prefix`, `publisher_prefix_next`, `role`, `book_notice_mail`, `smp`, `smp_path`, `import_images`, `ebook_store_status`, `news_category_edit`, `hondanaec_status`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, 'フロイデ株式会社', 'フロイデカブシキガイシャ', 'froide', 'froide', 'design1-1', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 0, '2.0', '', 0, '', '', 0, '', 0, NULL, NULL, NULL, 0, NULL, NULL, '2008-07-02 12:56:45', '2018-10-12 03:13:22', NULL),
(2, '株式会社フロイデール', 'カブシキガイシャフロイデール', 'froidale', 'froidale', 'design1-1', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', 0, '2.0', '', 0, '', '', 0, '', 0, NULL, NULL, 1, 0, NULL, NULL, '2008-07-02 12:56:45', '2018-10-12 03:13:22', NULL);

INSERT INTO `publisher_account` (`publisher_account_no`, `publisher_no`, `role_no`, `name`, `id`, `password`, `is_default`, `updated`) VALUES
(1, 1, 1, 'マスターアカウント', 'froide', 'froide', 1, '2021-06-24 09:37:41');
INSERT INTO `publisher_account` (`publisher_account_no`, `publisher_no`, `role_no`, `name`, `id`, `password`, `is_default`, `updated`) VALUES
(2, 2, 2, 'ウェブマスター', 'admin', 'admin', 1, '2021-06-24 09:37:41');

INSERT INTO publisher_netshop (publisher_no, netshop_no, public_status, display_order, c_stamp) VALUES
(2, 1, 1, 1, current_timestamp),
(2, 2, 1, 2, current_timestamp),
(2, 4, 1, 4, current_timestamp),
(2, 6, 1, 6, current_timestamp),
(2, 8, 1, 8, current_timestamp),
(2, 9, 1, 9, current_timestamp),
(2, 10, 1, 10, current_timestamp),
(2, 13, 1, 12, current_timestamp),
(2, 14, 1, 13, current_timestamp),
(2, 17, 1, 15, current_timestamp);

INSERT INTO `role` (`role_no`, `publisher_no`, `name`, `is_default`) VALUES
(1, NULL, 'マスターアカウント', 0);
INSERT INTO `role` (`role_no`, `publisher_no`, `name`, `is_default`) VALUES
(2, 0, 'ウェブマスター', 1);

INSERT INTO `role_access` (`role_no`, `access_no`) VALUES
(3, 6);
INSERT INTO `role_access` (`role_no`, `access_no`) VALUES
(3, 7);
INSERT INTO `role_access` (`role_no`, `access_no`) VALUES
(3, 8);
INSERT INTO `role_access` (`role_no`, `access_no`) VALUES
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(3, 21),
(3, 22),
(3, 23),
(3, 24),
(3, 25),
(3, 26),
(3, 27),
(3, 28),
(3, 29),
(3, 30),
(3, 31),
(3, 32),
(3, 33),
(3, 34),
(3, 35),
(3, 36),
(3, 37),
(3, 38),
(3, 39),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 11),
(4, 12),
(4, 13),
(4, 14),
(4, 15),
(4, 16),
(4, 17),
(4, 18),
(4, 19),
(4, 20),
(4, 21),
(4, 22),
(4, 23),
(4, 24),
(4, 25),
(4, 26),
(4, 27),
(4, 28),
(4, 29),
(4, 30),
(4, 31),
(4, 32),
(4, 33),
(4, 34),
(4, 35),
(4, 36),
(4, 37),
(4, 38),
(4, 39),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(5, 19),
(5, 20),
(5, 21),
(5, 22),
(5, 23),
(5, 24),
(5, 25),
(5, 26),
(5, 27),
(5, 28),
(5, 29),
(5, 30),
(5, 31),
(5, 32),
(5, 33),
(5, 34),
(5, 35),
(5, 36);

INSERT INTO `role_access_book` (`role_no`, `access_book_no`) VALUES
(3, 23);
INSERT INTO `role_access_book` (`role_no`, `access_book_no`) VALUES
(4, 23);
INSERT INTO `role_access_book` (`role_no`, `access_book_no`) VALUES
(5, 23);

INSERT INTO `stock_status` (`stock_status_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(1, '在庫あり', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `stock_status` (`stock_status_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(2, '在庫僅少', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `stock_status` (`stock_status_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(3, '重版中', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL);
INSERT INTO `stock_status` (`stock_status_no`, `name`, `note`, `c_stamp`, `u_stamp`, `d_stamp`) VALUES
(4, '未刊・予約受付中', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(5, '品切れ・重版未定', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(6, '絶版', NULL, '2008-07-02 12:53:21', '2008-07-02 12:53:21', NULL),
(7, 'オンデマンド制作', NULL, '2009-06-19 06:51:42', '2009-06-19 06:51:42', NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

DROP TABLE IF EXISTS `sales_tax`;
CREATE TABLE `sales_tax` (
  `sales_tax_no` int(11) NOT NULL AUTO_INCREMENT,
  `late` decimal(7,5) NOT NULL,
  `start_date` datetime NOT NULL,
  PRIMARY KEY (`sales_tax_no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
INSERT INTO `sales_tax` (`sales_tax_no`, `late`, `start_date`) VALUES
(1, 0.1,'2019-10-01 00:00:00');
