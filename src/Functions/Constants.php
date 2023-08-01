<?php

// Boolean
const YES = 1;
const NO = 0;

// TinyInt
const MIN_UNSIGNED_TINYINT = 0;
const MAX_UNSIGNED_TINYINT = 255;
const MIN_SIGNED_TINYINT = -128;
const MAX_SIGNED_TINYINT = 127;

// SmallInt
const MIN_UNSIGNED_SMALLINT = 0;
const MAX_UNSIGNED_SMALLINT = 65535;
const MIN_SIGNED_SMALLINT = -32768;
const MAX_SIGNED_SMALLINT = 32767;

// MediumInt
const MIN_UNSIGNED_MEDIUMINT = 0;
const MAX_UNSIGNED_MEDIUMINT = 16777215;
const MIN_SIGNED_MEDIUMINT = -8388608;
const MAX_SIGNED_MEDIUMINT = 8388607;

// Int
const MIN_UNSIGNED_INT = 0;
const MAX_UNSIGNED_INT = 4294967295;
const MIN_SIGNED_INT = -2147483648;
const MAX_SIGNED_INT = 2147483647;

// BigInt
const MIN_UNSIGNED_BIGINT = 0;
const MAX_UNSIGNED_BIGINT = 18446744073709551615;
const MIN_SIGNED_BIGINT = -9223372036854775808;
const MAX_SIGNED_BIGINT = 9223372036854775807;

// Float
const MIN_SIGNED_FLOAT = -3.402823466E+38;
const MAX_SIGNED_FLOAT = -1.175494351E-38;
const MIN_UNSIGNED_FLOAT = 1.175494351E-38;
const MAX_UNSIGNED_FLOAT = 3.402823466E+38;

// Double
const MIN_SIGNED_DOUBLE = -1.7976931348623157E+308;
const MAX_SIGNED_DOUBLE = -2.2250738585072014E-308;
const MIN_UNSIGNED_DOUBLE = 2.2250738585072014E-308;
const MAX_UNSIGNED_DOUBLE = 1.7976931348623157E+308;

// Decimal
const MAX_DECIMAL_DIGIT = 65;

// DateTime
const DATETIME_FORMAT = 'Y-m-d H:i:s';
const DATETIME_MIN = '1000-01-01 00:00:00';
const DATETIME_MAX = '9999-12-31 23:59:59';

// Date
const DATE_FORMAT = 'Y-m-d';
const DATE_MIN = '1000-01-01';
const DATE_MAX = '9999-12-31';

// Timestamp
const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
const TIMESTAMP_MIN = '1970-01-01 00:00:01';
const TIMESTAMP_MAX = '2038-01-19 03:14:07';

// Year
const YEAR_MIN = 1901;
const YEAR_MAX = 2155;

// Char
const CHAR_MIN_LENGTH = 0;
const CHAR_MAX_LENGTH = 255;

// VarChar
const VARCHAR_MIN_LENGTH = 0;
const VARCHAR_MAX_LENGTH = 65535;

// Binary
const BINARY_MIN_BYTES = 0;
const BINARY_MAX_BYTES = 255;

// VarBinary
const VARBINARY_MIN_BYTES = 0;
const VARBINARY_MAX_BYTES = 65535;

// Blob
const TINYBLOB_MIN_LENGTH = 0;
const TINYBLOB_MAX_LENGTH = 255;
const BLOB_MIN_LENGTH = 0;
const BLOB_MAX_LENGTH = 65535;
const MEDIUMBLOB_MIN_LENGTH = 0;
const MEDIUMBLOB_MAX_LENGTH = 16777215;
const LONGBLOB_MIN_LENGTH = 0;
const LONGBLOB_MAX_LENGTH = 4294967295;

// Text
const TINYTEXT_MIN_LENGTH = 0;
const TINYTEXT_MAX_LENGTH = 255;
const TEXT_MIN_LENGTH = 0;
const TEXT_MAX_LENGTH = 65535;
const MEDIUMTEXT_MIN_LENGTH = 0;
const MEDIUMTEXT_MAX_LENGTH = 16777215;
const LONGTEXT_MIN_LENGTH = 0;
const LONGTEXT_MAX_LENGTH = 4294967295;
