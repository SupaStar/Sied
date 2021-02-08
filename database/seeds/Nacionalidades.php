<?php

use Illuminate\Database\Seeder;

class Nacionalidades extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nacionalidades')->insert([
            [
                "c_country" => 223, 
                "country" => "MEXICANA", 
                "code" => "MEX"], 
            [
                "c_country" => 215, 
                "country" => "ESTADOUNIDENSE", 
                "code" => "USA"], 
            [
                "c_country" => 101, 
                "country" => "NAMIBIANA", 
                "code" => "NAM"], 
            [
                "c_country" => 103, 
                "country" => "ANGOLESA", 
                "code" => "AGO"], 
            [
                "c_country" => 104, 
                "country" => "ARGELIANA", 
                "code" => "DZA"], 
            [
                "c_country" => 105, 
                "country" => "DE BENNIN", 
                "code" => "BEN"], 
            [
                "c_country" => 106, 
                "country" => "BOTSWANESA", 
                "code" => "BWA"], 
            [
                "c_country" => 107, 
                "country" => "BURUNDESA", 
                "code" => "BDI"], 
            [
                "c_country" => 108, 
                "country" => "DE CABO VERDE", 
                "code" => "CPV"], 
            [
                "c_country" => 109, 
                "country" => "COMORENSE", 
                "code" => "COM"], 
            [
                "c_country" => 110, 
                "country" => "CONGOLESA", 
                "code" => "COD"], 
            [
                "c_country" => 111, 
                "country" => "MARFILEÑA", 
                "code" => "COG"], 
            [
                "c_country" => 112, 
                "country" => "CHADIANA", 
                "code" => "TCD"], 
            [
                "c_country" => 113, 
                "country" => "DE DJIBOUTI", 
                "code" => "DJI"], 
            [
                "c_country" => 114, 
                "country" => "EGIPCIA", 
                "code" => "EGY"], 
            [
                "c_country" => 115, 
                "country" => "ETIOPE", 
                "code" => "ETH"], 
            [
                "c_country" => 116, 
                "country" => "GABONESA", 
                "code" => "GAB"], 
            [
                "c_country" => 117, 
                "country" => "GAMBIANA", 
                "code" => "GMB"], 
            [
                "c_country" => 118, 
                "country" => "GHANATA", 
                "code" => "GHA"], 
            [
                "c_country" => 119, 
                "country" => "GUINEA", 
                "code" => "GNB"], 
            [
                "c_country" => 120, 
                "country" => "GUINEA", 
                "code" => "GIN"], 
            [
                "c_country" => 121, 
                "country" => "GUINEA ECUATORIANA", 
                "code" => "GNQ"], 
            [
                "c_country" => 122, 
                "country" => "LIBIA", 
                "code" => "LBY"], 
            [
                "c_country" => 123, 
                "country" => "KENIANA", 
                "code" => "KEN"], 
            [
                "c_country" => 124, 
                "country" => "LESOTHENSE", 
                "code" => "LSO"], 
            [
                "c_country" => 125, 
                "country" => "LIBERIANA", 
                "code" => "LBR"], 
            [
                "c_country" => 127, 
                "country" => "MALAWIANA", 
                "code" => "MWI"], 
            [
                "c_country" => 128, 
                "country" => "MALIENSE", 
                "code" => "MLI"], 
            [
                "c_country" => 129, 
                "country" => "MARROQUI", 
                "code" => "MAR"], 
            [
                "c_country" => 130, 
                "country" => "MAURICIANA", 
                "code" => "MUS"], 
            [
                "c_country" => 131, 
                "country" => "MAURITANA", 
                "code" => "MRT"], 
            [
                "c_country" => 132, 
                "country" => "MOZAMBIQUEÑA", 
                "code" => "MOZ"], 
            [
                "c_country" => 133, 
                "country" => "NIGERINA", 
                "code" => "NER"], 
            [
                "c_country" => 134, 
                "country" => "NIGERIANA", 
                "code" => "NGA"], 
            [
                "c_country" => 135, 
                "country" => "CENTRO AFRICANA", 
                "code" => "CAF"], 
            [
                "c_country" => 136, 
                "country" => "CAMERUNESA", 
                "code" => "CMR"], 
            [
                "c_country" => 137, 
                "country" => "TANZANIANA", 
                "code" => "TZA"], 
            [
                "c_country" => 139, 
                "country" => "RWANDESA", 
                "code" => "RWA"], 
            [
                "c_country" => 140, 
                "country" => "DEL SAHARA", 
                "code" => "ESH"], 
            [
                "c_country" => 141, 
                "country" => "DE SANTO TOME", 
                "code" => "STP"], 
            [
                "c_country" => 142, 
                "country" => "SENEGALESA", 
                "code" => "SEN"], 
            [
                "c_country" => 143, 
                "country" => "DE SEYCHELLES", 
                "code" => "SYC"], 
            [
                "c_country" => 144, 
                "country" => "SIERRA LEONESA", 
                "code" => "SLE"], 
            [
                "c_country" => 145, 
                "country" => "SOMALI", 
                "code" => "SOM"], 
            [
                "c_country" => 146, 
                "country" => "SUDAFRICANA", 
                "code" => "ZAF"], 
            [
                "c_country" => 147, 
                "country" => "SUDANESA", 
                "code" => "SDN"], 
            [
                "c_country" => 148, 
                "country" => "SWAZI", 
                "code" => "SWZ"], 
            [
                "c_country" => 149, 
                "country" => "TOGOLESA", 
                "code" => "TGO"], 
            [
                "c_country" => 150, 
                "country" => "TUNECINA", 
                "code" => "TUN"], 
            [
                "c_country" => 151, 
                "country" => "UGANDESA", 
                "code" => "UGA"], 
            [
                "c_country" => 152, 
                "country" => "ZAIRANA", 
                "code" => "ZAR"], 
            [
                "c_country" => 153, 
                "country" => "ZAMBIANA", 
                "code" => "ZMB"], 
            [
                "c_country" => 154, 
                "country" => "DE ZIMBAWI", 
                "code" => "ZWE"], 
            [
                "c_country" => 201, 
                "country" => "ARGENTINA", 
                "code" => "ARG"], 
            [
                "c_country" => 202, 
                "country" => "BAHAMEÑA", 
                "code" => "BHS"], 
            [
                "c_country" => 203, 
                "country" => "DE BARBADOS", 
                "code" => "BRB"], 
            [
                "c_country" => 204, 
                "country" => "BELICEÑA", 
                "code" => "BLZ"], 
            [
                "c_country" => 205, 
                "country" => "BOLIVIANA", 
                "code" => "BOL"], 
            [
                "c_country" => 206, 
                "country" => "BRASILEÑA", 
                "code" => "BRA"], 
            [
                "c_country" => 207, 
                "country" => "CANADIENSE", 
                "code" => "CAN"], 
            [
                "c_country" => 208, 
                "country" => "COLOMBIANA", 
                "code" => "COL"], 
            [
                "c_country" => 209, 
                "country" => "COSTARRICENSE", 
                "code" => "CRI"], 
            [
                "c_country" => 210, 
                "country" => "CUBANA", 
                "code" => "CUB"], 
            [
                "c_country" => 211, 
                "country" => "CHILENA", 
                "code" => "CHL"], 
            [
                "c_country" => 212, 
                "country" => "DOMINICA", 
                "code" => "DMA"], 
            [
                "c_country" => 214, 
                "country" => "SALVADOREÑA", 
                "code" => "SLV"], 
            [
                "c_country" => 216, 
                "country" => "GRANADINA", 
                "code" => "VCT"], 
            [
                "c_country" => 217, 
                "country" => "GUATEMALTECA", 
                "code" => "GTM"], 
            [
                "c_country" => 218, 
                "country" => "BRITANICA", 
                "code" => "VGB"], 
            [
                "c_country" => 219, 
                "country" => "GUYANESA", 
                "code" => "GUY"], 
            [
                "c_country" => 220, 
                "country" => "HAITIANA", 
                "code" => "HTI"], 
            [
                "c_country" => 221, 
                "country" => "HONDUREÑA", 
                "code" => "HND"], 
            [
                "c_country" => 222, 
                "country" => "JAMAIQUINA", 
                "code" => "JAM"], 
            [
                "c_country" => 224, 
                "country" => "NICARAGUENSE", 
                "code" => "NIC"], 
            [
                "c_country" => 225, 
                "country" => "PANAMEÑA", 
                "code" => "PAN"], 
            [
                "c_country" => 226, 
                "country" => "PARAGUAYA", 
                "code" => "PRY"], 
            [
                "c_country" => 227, 
                "country" => "PERUANA", 
                "code" => "PER"], 
            [
                "c_country" => 228, 
                "country" => "PUERTORRIQUEÑA", 
                "code" => "PRI"], 
            [
                "c_country" => 229, 
                "country" => "DOMINICANA", 
                "code" => "DOM"], 
            [
                "c_country" => 230, 
                "country" => "SANTA LUCIENSE", 
                "code" => "LCA"], 
            [
                "c_country" => 231, 
                "country" => "SURINAMENSE", 
                "code" => "SUR"], 
            [
                "c_country" => 232, 
                "country" => "TRINITARIA", 
                "code" => "TTO"], 
            [
                "c_country" => 233, 
                "country" => "URUGUAYA", 
                "code" => "URY"], 
            [
                "c_country" => 234, 
                "country" => "VENEZOLANA", 
                "code" => "VEN"], 
            [
                "c_country" => 299, 
                "country" => "AMERICANA", 
                "code" => "USA"], 
            [
                "c_country" => 301, 
                "country" => "AFGANA", 
                "code" => "AFG"], 
            [
                "c_country" => 303, 
                "country" => "DE BAHREIN", 
                "code" => "BHR"], 
            [
                "c_country" => 305, 
                "country" => "BHUTANESA", 
                "code" => "BTN"], 
            [
                "c_country" => 306, 
                "country" => "BIRMANA", 
                "code" => "BUR"], 
            [
                "c_country" => 307, 
                "country" => "NORCOREANA", 
                "code" => "PRK"], 
            [
                "c_country" => 308, 
                "country" => "SUDCOREANA", 
                "code" => "KOR"], 
            [
                "c_country" => 309, 
                "country" => "CHINA", 
                "code" => "CHN"], 
            [
                "c_country" => 310, 
                "country" => "CHIPRIOTA", 
                "code" => "CYP"], 
            [
                "c_country" => 311, 
                "country" => "ARABE", 
                "code" => "SAU"], 
            [
                "c_country" => 312, 
                "country" => "FILIPINA", 
                "code" => "PHL"], 
            [
                "c_country" => 313, 
                "country" => "HINDU", 
                "code" => "IND"], 
            [
                "c_country" => 314, 
                "country" => "INDONESA", 
                "code" => "IDN"], 
            [
                "c_country" => 315, 
                "country" => "IRAQUI", 
                "code" => "IRQ"], 
            [
                "c_country" => 316, 
                "country" => "IRANI", 
                "code" => "IRN"], 
            [
                "c_country" => 317, 
                "country" => "ISRAELI", 
                "code" => "ISR"], 
            [
                "c_country" => 318, 
                "country" => "JAPONESA", 
                "code" => "JPN"], 
            [
                "c_country" => 319, 
                "country" => "JORDANA", 
                "code" => "JOR"], 
            [
                "c_country" => 320, 
                "country" => "CAMBOYANA", 
                "code" => "KHM"], 
            [
                "c_country" => 321, 
                "country" => "KUWAITI", 
                "code" => "KWT"], 
            [
                "c_country" => 322, 
                "country" => "LIBANESA", 
                "code" => "LBN"], 
            [
                "c_country" => 323, 
                "country" => "MALASIA", 
                "code" => "MYS"], 
            [
                "c_country" => 324, 
                "country" => "MALDIVA", 
                "code" => "MDV"], 
            [
                "c_country" => 325, 
                "country" => "MONGOLESA", 
                "code" => "MNG"], 
            [
                "c_country" => 326, 
                "country" => "NEPALESA", 
                "code" => "NPL"], 
            [
                "c_country" => 327, 
                "country" => "OMANESA", 
                "code" => "OMN"], 
            [
                "c_country" => 328, 
                "country" => "PAKISTANI", 
                "code" => "PAK"], 
            [
                "c_country" => 329, 
                "country" => "DEL QATAR", 
                "code" => "QAT"], 
            [
                "c_country" => 330, 
                "country" => "SIRIA", 
                "code" => "SYR"], 
            [
                "c_country" => 331, 
                "country" => "LAOSIANA", 
                "code" => "LAO"], 
            [
                "c_country" => 332, 
                "country" => "SINGAPORENSE", 
                "code" => "SGP"], 
            [
                "c_country" => 334, 
                "country" => "TAILANDESA", 
                "code" => "THA"], 
            [
                "c_country" => 335, 
                "country" => "TAIWANESA", 
                "code" => "TWN"], 
            [
                "c_country" => 336, 
                "country" => "TURCA", 
                "code" => "TUR"], 
            [
                "c_country" => 337, 
                "country" => "NORVIETNAMITA", 
                "code" => "VNM"], 
            [
                "c_country" => 339, 
                "country" => "YEMENI", 
                "code" => "YEM"], 
            [
                "c_country" => 401, 
                "country" => "ALBANESA", 
                "code" => "ALB"], 
            [
                "c_country" => 403, 
                "country" => "ALEMANA", 
                "code" => "DEU"], 
            [
                "c_country" => 404, 
                "country" => "ANDORRANA", 
                "code" => "AND"], 
            [
                "c_country" => 405, 
                "country" => "AUSTRIACA", 
                "code" => "AUT"], 
            [
                "c_country" => 406, 
                "country" => "BELGA", 
                "code" => "BEL"], 
            [
                "c_country" => 407, 
                "country" => "BULGARA", 
                "code" => "BGR"], 
            [
                "c_country" => 408, 
                "country" => "CHECOSLOVACA", 
                "code" => "CSK"], 
            [
                "c_country" => 409, 
                "country" => "DANESA", 
                "code" => "DNK"], 
            [
                "c_country" => 410, 
                "country" => "VATICANA", 
                "code" => "VAT"], 
            [
                "c_country" => 411, 
                "country" => "ESPAÑOLA", 
                "code" => "ESP"], 
            [
                "c_country" => 412, 
                "country" => "FINLANDESA", 
                "code" => "FIN"], 
            [
                "c_country" => 413, 
                "country" => "FRANCESA", 
                "code" => "FRA"], 
            [
                "c_country" => 414, 
                "country" => "GRIEGA", 
                "code" => "GRC"], 
            [
                "c_country" => 415, 
                "country" => "HUNGARA", 
                "code" => "HUN"], 
            [
                "c_country" => 416, 
                "country" => "IRLANDESA", 
                "code" => "IRL"], 
            [
                "c_country" => 417, 
                "country" => "ISLANDESA", 
                "code" => "ISL"], 
            [
                "c_country" => 418, 
                "country" => "ITALIANA", 
                "code" => "ITA"], 
            [
                "c_country" => 419, 
                "country" => "LIECHTENSTENSE", 
                "code" => "LIE"], 
            [
                "c_country" => 420, 
                "country" => "LUXEMBURGUESA", 
                "code" => "LUX"], 
            [
                "c_country" => 421, 
                "country" => "MALTESA", 
                "code" => "MLT"], 
            [
                "c_country" => 422, 
                "country" => "MONEGASCA", 
                "code" => "MCO"], 
            [
                "c_country" => 423, 
                "country" => "NORUEGA", 
                "code" => "NOR"], 
            [
                "c_country" => 424, 
                "country" => "HOLANDESA", 
                "code" => "NLD"], 
            [
                "c_country" => 426, 
                "country" => "PORTUGUESA", 
                "code" => "PRT"], 
            [
                "c_country" => 427, 
                "country" => "BRITANICA", 
                "code" => "IOT"], 
            [
                "c_country" => 428, 
                "country" => "SOVIETICA BIELORRUSA", 
                "code" => "BLR"], 
            [
                "c_country" => 429, 
                "country" => "SOVIETICA UCRANIANA", 
                "code" => "UKR"], 
            [
                "c_country" => 430, 
                "country" => "RUMANA", 
                "code" => "ROM"], 
            [
                "c_country" => 431, 
                "country" => "SAN MARINENSE", 
                "code" => "SMR"], 
            [
                "c_country" => 432, 
                "country" => "SUECA", 
                "code" => "SWE"], 
            [
                "c_country" => 433, 
                "country" => "SUIZA", 
                "code" => "CHE"], 
            [
                "c_country" => 435, 
                "country" => "YUGOSLAVA", 
                "code" => "YUG"], 
            [
                "c_country" => 501, 
                "country" => "AUSTRALIANA", 
                "code" => "AUS"], 
            [
                "c_country" => 502, 
                "country" => "FIJIANA", 
                "code" => "FJI"], 
            [
                "c_country" => 503, 
                "country" => "SALOMONESA", 
                "code" => "SLB"], 
            [
                "c_country" => 504, 
                "country" => "NAURUANA", 
                "code" => "NRU"], 
            [
                "c_country" => 506, 
                "country" => "PAPUENSE", 
                "code" => "PNG"], 
            [
                "c_country" => 507, 
                "country" => "SAMOANA", 
                "code" => "WSM"], 
            [
                "c_country" => 667, 
                "country" => "ESLOVACA", 
                "code" => "SVK"], 
            [
                "c_country" => 609, 
                "country" => "BURKINA FASO", 
                "code" => "BFA"], 
            [
                "c_country" => 621, 
                "country" => "ESTONIA", 
                "code" => "EST"], 
            [
                "c_country" => 624, 
                "country" => "MICRONECIA", 
                "code" => "FSM"], 
            [
                "c_country" => 625, 
                "country" => "REINO UNIDO(DEPEN. TET. BRIT.)", 
                "code" => "GBD"], 
            [
                "c_country" => 626, 
                "country" => "REINO UNIDO(BRIT. DEL EXT.)", 
                "code" => "GBN"], 
            [
                "c_country" => 627, 
                "country" => "REINO UNIDO(C. BRIT. DEL EXT.)", 
                "code" => "GBO"], 
            [
                "c_country" => 629, 
                "country" => "REINO UNIDO", 
                "code" => "GBR"], 
            [
                "c_country" => 642, 
                "country" => "KIRGUISTAN", 
                "code" => "KGZ"], 
            [
                "c_country" => 645, 
                "country" => "LITUANIA ", 
                "code" => "LTU"], 
            [
                "c_country" => 648, 
                "country" => "MOLDOVIA, REPUBLICA DE", 
                "code" => "MDA"], 
            [
                "c_country" => 650, 
                "country" => "MACEDONIA", 
                "code" => "MKD"], 
            [
                "c_country" => 668, 
                "country" => "ESLOVENIA", 
                "code" => "SVN"], 
            [
                "c_country" => 684, 
                "country" => "ESLOVAQUIA", 
                "code" => "XES"]

          ]);
    }
}
