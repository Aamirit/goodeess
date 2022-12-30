-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2022 at 06:30 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goodies`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'goodiesadmin@gmail.com', 'admin123', '2022-03-25 14:00:33', '2022-03-25 14:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` int(11) NOT NULL,
  `campaign_crm_id` int(15) DEFAULT NULL,
  `campaign_title` varchar(255) NOT NULL,
  `country_code` varchar(5) NOT NULL,
  `campaign_settings` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `campaign_crm_id`, `campaign_title`, `country_code`, `campaign_settings`, `created_at`, `updated_at`) VALUES
(2, 17, 'France', '20205', ' g dfg gdfg fdg f ', '2022-03-25 20:30:32', '2022-03-30 17:26:44'),
(3, 234, 'dfsd', '23432', 'fdgh fhfgh gf g hg  ', '2022-03-28 13:55:33', '2022-03-28 13:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offer_id` int(11) NOT NULL,
  `offer_crm_id` int(15) DEFAULT NULL,
  `campaign_id` int(11) NOT NULL,
  `offer_title` varchar(255) NOT NULL,
  `offer_slug` varchar(255) NOT NULL,
  `offer_price` varchar(11) DEFAULT NULL,
  `country` varchar(5) DEFAULT NULL,
  `offer_settings` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`offer_id`, `offer_crm_id`, `campaign_id`, `offer_title`, `offer_slug`, `offer_price`, `country`, `offer_settings`, `created_at`, `updated_at`) VALUES
(1, 1333, 2, 'Resistance Elastic Bands', 'resistance-elastic-bands', '1.95', '33333', 'efsd g fh gfhf', '2022-03-25 20:53:19', '2022-03-31 15:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `securion_pay_token` text DEFAULT NULL,
  `offer_crm_id` int(11) DEFAULT NULL,
  `import_lead_post_data` text DEFAULT NULL,
  `import_lead_response` text DEFAULT NULL,
  `import_order_post_data` text DEFAULT NULL,
  `import_order_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `email`, `order_id`, `securion_pay_token`, `offer_crm_id`, `import_lead_post_data`, `import_lead_response`, `import_order_post_data`, `import_order_response`, `created_at`, `updated_at`) VALUES
(8, 'Dawood', 'Faiz', 'dawoodfaiz.2@gmail.com', 'FD2C496D92', 'tok_J8H1jfZBd6xC9o55fWVNwrsY', 1333, '{\"address1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"campaignId\":\"17\",\"city\":\"Multan\",\"country\":\"DE\",\"emailAddress\":\"dawoodfaiz.2@gmail.com\",\"firstName\":\"Dawood\",\"ipAddress\":\"::1\",\"lastName\":\"Faiz\",\"loginId\":\"developers-vipresponse\",\"password\":\"dev@sit\",\"phoneNumber\":\"03472406998\",\"postalCode\":\"60000\",\"sessionId\":\"1156b7e71c8449cda7ef68668bba06d8\",\"billShipSame\":\"on\"}', '{\"result\":\"SUCCESS\",\"message\":{\"address1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"campaignId\":17,\"city\":\"Multan\",\"country\":\"DE\",\"emailAddress\":\"dawoodfaiz.2@gmail.com\",\"firstName\":\"Dawood\",\"ipAddress\":\"::1\",\"lastName\":\"Faiz\",\"phoneNumber\":\"03472406998\",\"postalCode\":\"60000\",\"sessionId\":\"1156b7e71c8449cda7ef68668bba06d8\",\"billShipSame\":true,\"shipFirstName\":\"Dawood\",\"shipLastName\":\"Faiz\",\"shipAddress1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"shipCity\":\"Multan\",\"shipCountry\":\"DE\",\"shipPostalCode\":\"60000\",\"orderStatus\":\"PARTIAL\",\"orderType\":\"NEW_SALE\",\"customerId\":20972,\"agentUserId\":116798,\"newCustomFields\":{\"custom_Select_Color\":{\"customFieldId\":1,\"campaignId\":17,\"fieldName\":\"Select Color\",\"fieldType\":\"STR\",\"extra\":\"100\",\"safeName\":\"custom_Select_Color\",\"fieldValue\":null}},\"address2\":\"\",\"shipAddress2\":\"\",\"dateCreated\":\"2022-03-31 12:10:10\",\"dateUpdated\":\"2022-03-31 12:10:10\",\"orderId\":\"FD2C496D92\"}}', '{\"address1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"city\":\"Multan\",\"country\":\"DE\",\"emailAddress\":\"dawoodfaiz.2@gmail.com\",\"firstName\":\"Dawood\",\"ipAddress\":\"::1\",\"lastName\":\"Faiz\",\"loginId\":\"developers-vipresponse\",\"password\":\"dev@sit\",\"paySource\":\"PREPAID\",\"phoneNumber\":\"03472406998\",\"postalCode\":\"60000\",\"sessionId\":\"1156b7e71c8449cda7ef68668bba06d8\",\"campaignId\":\"17\",\"product1_id\":\"1333\",\"product1_price\":\"1.95\",\"product1_qty\":1,\"shipAddress1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"shipCity\":\"Multan\",\"shipPostalCode\":\"60000\",\"shipCountry\":\"DE\"}', '{\"result\":\"SUCCESS\",\"message\":{\"taxExemption\":null,\"orderId\":\"FD2C496D92\",\"ipAddress\":\"::1\",\"sourceId\":null,\"sourceValue1\":null,\"sourceValue2\":null,\"sourceValue3\":null,\"sourceValue4\":null,\"sourceValue5\":null,\"shipCarrier\":null,\"shipMethod\":null,\"profileName\":null,\"dateCreated\":\"2022-03-31 12:10:11\",\"orderType\":\"NEW_SALE\",\"orderStatus\":\"PENDING\",\"reviewStatus\":\"PENDING\",\"totalAmount\":1.95,\"campaignName\":\"FR - White Checkout Form\",\"orderValue\":\"1.95\",\"customerId\":20972,\"name\":\"Dawood Faiz\",\"emailAddress\":\"dawoodfaiz.2@gmail.com\",\"phoneNumber\":\"03472406998\",\"firstName\":\"Dawood\",\"lastName\":\"Faiz\",\"companyName\":null,\"address1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"address2\":null,\"shipmentInsured\":null,\"shipmentInsurancePrice\":null,\"insuranceCharged\":0,\"city\":\"Multan\",\"state\":null,\"country\":\"DE\",\"postalCode\":\"60000\",\"shipFirstName\":\"Dawood\",\"shipLastName\":\"Faiz\",\"shipCompanyName\":null,\"shipAddress1\":\"NAI BASTI, P\\/O RIAZ ABAD, LUTHAR, TEHSIL MULTAN SADDAR, DISTRICT MULTAN PAKISTAN, Multan, Pakistan\",\"shipAddress2\":null,\"shipCity\":\"Multan\",\"shipState\":null,\"shipCountry\":\"DE\",\"shipPostalCode\":\"60000\",\"custom1\":null,\"custom2\":null,\"custom3\":null,\"custom4\":null,\"custom5\":null,\"paySource\":\"PREPAID\",\"cardType\":null,\"cardLast4\":null,\"cardExpiryDate\":null,\"achAccountHolderType\":null,\"achAccountType\":null,\"achRoutingNumber\":null,\"achNameOnAccount\":null,\"achAccountNumber\":null,\"couponCode\":null,\"agentUserId\":116798,\"basePrice\":\"1.95\",\"baseShipping\":\"0.00\",\"voiceLogNumber\":null,\"discountPrice\":\"0.00\",\"salesTax\":\"0.00\",\"salesUrl\":null,\"surcharge\":\"0.00\",\"shipUpcharge\":null,\"shipProfileId\":null,\"currencySymbol\":\"\\u20ac\",\"currencyCode\":\"EUR\",\"campaignId\":17,\"merchantTxnId\":null,\"ibanLast4\":null,\"originalCycleNumber\":1,\"prepaidType\":null,\"subTotal\":\"1.95\",\"shipTotal\":\"0.00\",\"taxTotal\":\"0.00\",\"totalDiscount\":\"0.00\",\"totalRefundRemaining\":\"1.95\",\"amountPaid\":\"1.95\",\"items\":[{\"productId\":\"1333\",\"name\":\"Resistance Elastic Bands\",\"qty\":\"1\",\"productSku\":\"V0103154-S1\",\"actualProductId\":\"1314\",\"replacedByOrderItemId\":\"\",\"variantDetailId\":\"\",\"title\":\"\",\"variantSku\":\"\",\"shipping\":\"0.00\",\"price\":\"1.95\",\"merchantId\":\"\",\"descriptor\":\"\",\"initialSalesTax\":\"0.00\",\"customSalesTax\":\"0.00\",\"purchaseId\":\"C247815F3C\",\"refundRemaining\":\"1.95\",\"merchantTxnId\":\"\",\"purchaseStatus\":\"PENDING\",\"billingCycleType\":\"RECURRING\",\"finalBillingCycle\":\"\",\"isPreauthVoid\":\"0\",\"nextBillDate\":\"\",\"trialEnabled\":\"1\",\"trialType\":\"STANDARD\",\"trialAuthType\":\"\",\"regularPrice\":\"24.95\",\"productQty\":\"1\",\"cycle1_billDelay\":\"14\",\"cycle2_price\":\"24.95\",\"cycle2_shipPrice\":\"0.00\",\"cycle2_isShippable\":\"0\",\"cycle2_billDelay\":\"30\",\"cycle3_price\":\"24.95\",\"cycle3_shipPrice\":\"0.00\",\"cycle3_isShippable\":\"0\",\"cycle3_billDelay\":\"30\",\"lastCustomCycle\":\"4\",\"purchaseCycle\":\"1\",\"txnType\":\"SALE\",\"cancellationScheduled\":\"0\",\"cancelAfterDate\":\"\",\"pauseScheduled\":\"0\",\"billingCycleNumber\":\"1\",\"staggerIntervalCycles\":\"\",\"staggerFulfillments\":\"0\",\"recycleFailedStatus\":\"0\",\"recycleReference\":\"0\"}],\"paypalUrl\":null,\"paypalSdkParams\":null}}', '2022-03-31 16:10:07', '2022-03-31 16:10:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
