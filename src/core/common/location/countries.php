<?php
namespace core\common\location;
use core\common\enum;

/**
 * country short summary.
 *
 * country description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class countries extends enum
{
    protected $array = array(
        "AL"=>"ALBANIA",
        "DZ"=>"ALGERIA",
        "AD"=>"ANDORRA",
        "AO"=>"ANGOLA",
        "AI"=>"ANGUILLA",
        "AG"=>"ANTIGUA & BARBUDA",
        "AR"=>"ARGENTINA",
        "AM"=>"ARMENIA",
        "AW"=>"ARUBA",
        "AU"=>"AUSTRALIA",
        "AT"=>"AUSTRIA",
        "AZ"=>"AZERBAIJAN",
        "BS"=>"BAHAMAS",
        "BH"=>"BAHRAIN",
        "BB"=>"BARBADOS",
        "BY"=>"BELARUS",
        "BE"=>"BELGIUM",
        "BZ"=>"BELIZE",
        "BJ"=>"BENIN",
        "BM"=>"BERMUDA",
        "BT"=>"BHUTAN",
        "BO"=>"BOLIVIA",
        "BA"=>"BOSNIA & HERZEGOVINA",
        "BW"=>"BOTSWANA",
        "BR"=>"BRAZIL",
        "VG"=>"BRITISH VIRGIN ISLANDS",
        "BN"=>"BRUNEI",
        "BG"=>"BULGARIA",
        "BF"=>"BURKINA FASO",
        "BI"=>"BURUNDI",
        "KH"=>"CAMBODIA",
        "CM"=>"CAMEROON",
        "CA"=>"CANADA",
        "CV"=>"CAPE VERDE",
        "KY"=>"CAYMAN ISLANDS",
        "TD"=>"CHAD",
        "CL"=>"CHILE",
        "C2"=>"CHINA",
        "CO"=>"COLOMBIA",
        "KM"=>"COMOROS",
        "CG"=>"CONGO - BRAZZAVILLE",
        "CD"=>"CONGO - KINSHASA",
        "CK"=>"COOK ISLANDS",
        "CR"=>"COSTA RICA",
        "CI"=>"CÔTE D’IVOIRE",
        "HR"=>"CROATIA",
        "CY"=>"CYPRUS",
        "CZ"=>"CZECH REPUBLIC",
        "DK"=>"DENMARK",
        "DJ"=>"DJIBOUTI",
        "DM"=>"DOMINICA",
        "DO"=>"DOMINICAN REPUBLIC",
        "EC"=>"ECUADOR",
        "EG"=>"EGYPT",
        "SV"=>"EL SALVADOR",
        "ER"=>"ERITREA",
        "EE"=>"ESTONIA",
        "ET"=>"ETHIOPIA",
        "FK"=>"FALKLAND ISLANDS",
        "FO"=>"FAROE ISLANDS",
        "FJ"=>"FIJI",
        "FI"=>"FINLAND",
        "FR"=>"FRANCE",
        "GF"=>"FRENCH GUIANA",
        "PF"=>"FRENCH POLYNESIA",
        "GA"=>"GABON",
        "GM"=>"GAMBIA",
        "GE"=>"GEORGIA",
        "DE"=>"GERMANY",
        "GI"=>"GIBRALTAR",
        "GR"=>"GREECE",
        "GL"=>"GREENLAND",
        "GD"=>"GRENADA",
        "GP"=>"GUADELOUPE",
        "GT"=>"GUATEMALA",
        "GN"=>"GUINEA",
        "GW"=>"GUINEA-BISSAU",
        "GY"=>"GUYANA",
        "HN"=>"HONDURAS",
        "HK"=>"HONG KONG SAR CHINA",
        "HU"=>"HUNGARY",
        "IS"=>"ICELAND",
        "IN"=>"INDIA",
        "ID"=>"INDONESIA",
        "IE"=>"IRELAND",
        "IL"=>"ISRAEL",
        "IT"=>"ITALY",
        "JM"=>"JAMAICA",
        "JP"=>"JAPAN",
        "JO"=>"JORDAN",
        "KZ"=>"KAZAKHSTAN",
        "KE"=>"KENYA",
        "KI"=>"KIRIBATI",
        "KW"=>"KUWAIT",
        "KG"=>"KYRGYZSTAN",
        "LA"=>"LAOS",
        "LV"=>"LATVIA",
        "LS"=>"LESOTHO",
        "LI"=>"LIECHTENSTEIN",
        "LT"=>"LITHUANIA",
        "LU"=>"LUXEMBOURG"
    );

    public function current()
    {
        return new country($this->array[$this->position]);
    }

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? new country($this->array[$offset]) : null;
    }

}

