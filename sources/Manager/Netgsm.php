<?php

namespace IPS\netgsm\Manager;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use RateLimit\Exception\LimitExceeded;
use RateLimit\Rate;
use RateLimit\RedisRateLimiter;
use Redis;

class _Netgsm
{
	/**
	 * @var string[]
	 */
	public static $countryCodes = [
		'AF' => 'Afghanistan',
		'AX' => 'Aland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua And Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia And Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, Democratic Republic',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote D\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands (Malvinas)',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island & Mcdonald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran, Islamic Republic Of',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IM' => 'Isle Of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KR' => 'Korea',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia, Federated States Of',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory, Occupied',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthelemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts And Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin',
		'PM' => 'Saint Pierre And Miquelon',
		'VC' => 'Saint Vincent And Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome And Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia And Sandwich Isl.',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard And Jan Mayen',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad And Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks And Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Viet Nam',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'WF' => 'Wallis And Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe',
	];

	/**
	 * @var null
	 */
	protected $usercode;

	/**
	 * @var null
	 */
	protected $password;

	/**
	 * @var null
	 */
	protected $senderName;

	/**
	 * Constructor
	 *
	 * @param          $usercode
	 * @param          $password
	 * @param string   $senderName
	 */
	public function __construct($usercode = null, $password = null, $senderName = null)
	{
		require_once \IPS\Application::getRootPath() . '/applications/netgsm/sources/vendor/autoload.php';

		$decryptedUsercode = null;
		if (\IPS\Settings::i()->netgsm_usercode) {
			$decryptedUsercode = \IPS\Text\Encrypt::fromTag(\IPS\Settings::i()->netgsm_usercode)->decrypt();
		}

		$decryptedPassword = null;
		if (\IPS\Settings::i()->netgsm_password) {
			$decryptedPassword = \IPS\Text\Encrypt::fromTag(\IPS\Settings::i()->netgsm_password)->decrypt();
		}

		$this->usercode = $usercode ?? $decryptedUsercode;
		$this->password = $password ?? $decryptedPassword;
		$this->senderName = $senderName ?? \IPS\Settings::i()->netgsm_sender_name;
	}

	/**
	 * @param string|PhoneNumber                $phoneNumber
	 * @param string|\IPS\netgsm\Phone\Message  $message
	 *
	 * @throws LimitExceeded|
	 * @return mixed
	 */
	public function sendSms($phoneNumber, $message)
	{
		$phoneNumbers = collect($phoneNumber)->map(function ($phoneNumber) {
			return $this->formatPhoneNumber($phoneNumber);
		})->unique()->each(function ($phoneNumber) use ($message) {
			$this->checkRateLimiter($phoneNumber);
			$this->addMessageLog($phoneNumber, $message);
		});

		if ($phoneNumbers->isNotEmpty()) {
			return \IPS\Http\Url::external('https://api.netgsm.com.tr/sms/send/get')->setQueryString([
				'usercode' => $this->usercode,
				'password' => $this->password,
				'msgheader' => $this->senderName,
				'gsmno' => implode(',', $phoneNumbers->toArray()),
				'message' => $message instanceof \IPS\netgsm\Phone\Message ? $message->message : $message
			])->request()->post();
		}
	}

	/**
	 * @param $phoneNumber
	 */
	protected function checkRateLimiter($phoneNumber): void
	{
		if (\IPS\REDIS_ENABLED && \IPS\STORE_METHOD === 'Redis') {
			$perMinute = \IPS\Settings::i()->netgsm_rate_limiter_per_minute ?? 100;

			$rateLimiter = new RedisRateLimiter(Rate::perMinute($perMinute), \IPS\Redis::i()->connection());
			$rateLimiter->limit($phoneNumber);
		}
	}

	/**
	 * @param $phoneNumber
	 * @param $message
	 */
	protected function addMessageLog($phoneNumber, $message)
	{
		\IPS\Db::i()->insert('netgsm_messages', [
			'phone_number' => $phoneNumber,
			'message' => $message,
			'message_sent_at' => time()
		]);
	}

	/**
	 * @param         $member
	 * @param  array  $update
	 *
	 * @return mixed
	 */
	public function updateVerificationStatus($member, array $update = array())
	{
		$id = $member instanceof \IPS\Member ? $member->member_id : $member;
		try {
			$previousEntry = \IPS\Db::i()->select('*', 'netgsm_verifications', [
				'member_id=?', $id
			])->first();

			return \IPS\Db::i()->update('netgsm_verifications', $update, [
				'member_id=?', $id
			]);
		} catch ( \UnderflowException $exception) {}

		return \IPS\Db::i()->insert('netgsm_verifications', array_merge([
			'member_id' => $id
		], $update));
	}

	/**
	 * @param $member
	 * @param $code
	 *
	 * @return bool
	 */
	public function confirmCode($member, $code): bool
	{
		try {
			\IPS\Db::i()->select('*', 'netgsm_verifications', [
				['member_id=?', $member->member_id],
				['code=?', $code]
			])->first();

			return true;
		} catch ( \UnderflowException $exception) {}

		return false;
	}

	/**
	 * @param $code
	 *
	 * @return array|mixed|string|string[]
	 */
	public function composeCodeValidationTextMessage($code)
	{
		$message = \IPS\Settings::i()->netgsm_registration_text_message;
		if (Str::contains($message, '{code}')) {
			return Str::replace('{code}', $code, $message);
		}
		return $code;
	}

	/**
	 * @return int
	 * @throws \Exception
	 */
	public function generateRandomCode(): int
	{
		return random_int(100000, 999999);
	}

	/**
	 * @param        $phoneNumber
	 * @param  null  $countryCode
	 *
	 * @return false|PhoneNumber|null
	 */
	public function validatePhoneNumber($phoneNumber, $countryCode = null)
	{
		try {
			$phoneUtil = PhoneNumberUtil::getInstance();
			$phoneNumberProto = $phoneUtil->parse($phoneNumber, $countryCode ?? \IPS\Settings::i()->netgsm_default_country_code, null, true);
			$valid = $phoneUtil->isValidNumber($phoneNumberProto);

			return $valid ? $phoneNumberProto : false;
		} catch (\libphonenumber\NumberParseException $e) {
			throw new \DomainException('The phone number you entered is not valid. Please try again.');
		}
	}

	/**
	 * @param $phoneNumber
	 *
	 * @return string
	 * @throws \libphonenumber\NumberParseException
	 */
	public function formatPhoneNumber($phoneNumber, int $format = PhoneNumberFormat::E164): string
	{
		$phoneUtil = PhoneNumberUtil::getInstance();
		if (!$phoneNumber instanceof PhoneNumber) {
			$phoneNumber = $phoneUtil->parse($phoneNumber);
		}

		return PhoneNumberUtil::getInstance()->format($phoneNumber, $format);
	}

	/**
	 * @param        $member
	 * @param  null  $refUrl
	 */
	public function setMemberAsUnverified($member, $refUrl = null): void
	{
		$member->members_bitoptions['validating'] = true;
		$member->save();

		\IPS\Db::i()->delete('core_validating', array('member_id=? and new_reg=1', $member->member_id));
		$this->updateVerificationStatus($member, [
			'verified' => 0,
			'verified_at' => null,
			'code' => null,
			'code_sent_at' => null
		]);

		$vid = md5($member->members_pass_hash . \IPS\Login::generateRandomString());
		\IPS\Db::i()->insert('core_validating', array(
			'vid'		   	=> $vid,
			'member_id'	 	=> $member->member_id,
			'entry_date'	=> time(),
			'new_reg'	   	=> 1,
			'ip_address'	=> $member->ip_address,
			'spam_flag'	 	=> ($member->members_bitoptions['bw_is_spammer']) ?: false,
			'user_verified'  => false,
			'email_sent'	=> null,
			'do_not_delete'	=> false,
			'ref'			=> $refUrl ? ( (string) $refUrl ) : NULL
		));
	}

	/**
	 * @param $member
	 */
	public function setMemberAsVerified($member): void
	{
		$member->members_bitoptions['validating'] = false;
		$member->save();

		\IPS\Db::i()->delete('core_validating', array('member_id=?', $member->member_id));
		$this->updateVerificationStatus($member, [
			'verified' => 1,
			'verified_at' => time(),
			'code' => null,
			'code_sent_at' => null
		]);
	}
}