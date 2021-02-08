<?php

use Illuminate\Database\Seeder;

class Paises extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('paises')->insert([
                    [
                        "code" => 303, 
                        "pais" => "MEXICO"], 
                    [
                        "code" => 302, 
                        "pais" => "ESTADOS UNIDOS DE AMERICA"], 
                    [
                        "code" => 102, 
                        "pais" => "AUSTRIA"], 
                    [
                        "code" => 103, 
                        "pais" => "BELGICA"], 
                    [
                        "code" => 104, 
                        "pais" => "BULGARIA"], 
                    [
                        "code" => 106, 
                        "pais" => "CHIPRE"], 
                    [
                        "code" => 107, 
                        "pais" => "DINAMARCA"], 
                    [
                        "code" => 108, 
                        "pais" => "ESPAÑA"], 
                    [
                        "code" => 109, 
                        "pais" => "FINLANDIA"], 
                    [
                        "code" => 110, 
                        "pais" => "FRANCIA"], 
                    [
                        "code" => 111, 
                        "pais" => "GRECIA"], 
                    [
                        "code" => 112, 
                        "pais" => "HUNGRIA"], 
                    [
                        "code" => 113, 
                        "pais" => "IRLANDA"], 
                    [
                        "code" => 115, 
                        "pais" => "ITALIA"], 
                    [
                        "code" => 117, 
                        "pais" => "LUXEMBURGO"], 
                    [
                        "code" => 118, 
                        "pais" => "MALTA"], 
                    [
                        "code" => 121, 
                        "pais" => "PAISES BAJOS"], 
                    [
                        "code" => 122, 
                        "pais" => "POLONIA"], 
                    [
                        "code" => 123, 
                        "pais" => "PORTUGAL"], 
                    [
                        "code" => 125, 
                        "pais" => "REINO UNIDO"], 
                    [
                        "code" => 126, 
                        "pais" => "ALEMANIA"], 
                    [
                        "code" => 128, 
                        "pais" => "RUMANIA"], 
                    [
                        "code" => 131, 
                        "pais" => "SUECIA"], 
                    [
                        "code" => 136, 
                        "pais" => "LETONIA"], 
                    [
                        "code" => 141, 
                        "pais" => "ESTONIA"], 
                    [
                        "code" => 142, 
                        "pais" => "LITUANIA"], 
                    [
                        "code" => 143, 
                        "pais" => "REPUBLICA CHECA"], 
                    [
                        "code" => 144, 
                        "pais" => "REPUBLICA ESLOVACA"], 
                    [
                        "code" => 147, 
                        "pais" => "ESLOVENIA"], 
                    [
                        "code" => 198, 
                        "pais" => "OTROS PAISES O TERRITORIOS DE LA UNION EUROPEA"], 
                    [
                        "code" => 101, 
                        "pais" => "ALBANIA"], 
                    [
                        "code" => 114, 
                        "pais" => "ISLANDIA"], 
                    [
                        "code" => 116, 
                        "pais" => "LIECHTENSTEIN"], 
                    [
                        "code" => 119, 
                        "pais" => "MONACO"], 
                    [
                        "code" => 120, 
                        "pais" => "NORUEGA"], 
                    [
                        "code" => 124, 
                        "pais" => "ANDORRA"], 
                    [
                        "code" => 129, 
                        "pais" => "SAN MARINO"], 
                    [
                        "code" => 130, 
                        "pais" => "SANTA SEDE"], 
                    [
                        "code" => 132, 
                        "pais" => "SUIZA"], 
                    [
                        "code" => 135, 
                        "pais" => "UCRANIA"], 
                    [
                        "code" => 137, 
                        "pais" => "MOLDAVIA"], 
                    [
                        "code" => 138, 
                        "pais" => "BELARUS"], 
                    [
                        "code" => 139, 
                        "pais" => "GEORGIA"], 
                    [
                        "code" => 145, 
                        "pais" => "BOSNIA Y HERZEGOVINA"], 
                    [
                        "code" => 146, 
                        "pais" => "CROACIA"], 
                    [
                        "code" => 148, 
                        "pais" => "ARMENIA"], 
                    [
                        "code" => 154, 
                        "pais" => "RUSIA"], 
                    [
                        "code" => 156, 
                        "pais" => "MACEDONIA "], 
                    [
                        "code" => 157, 
                        "pais" => "SERBIA"], 
                    [
                        "code" => 158, 
                        "pais" => "MONTENEGRO"], 
                    [
                        "code" => 170, 
                        "pais" => "GUERNESEY"], 
                    [
                        "code" => 171, 
                        "pais" => "SVALBARD Y JAN MAYEN"], 
                    [
                        "code" => 172, 
                        "pais" => "ISLAS FEROE"], 
                    [
                        "code" => 173, 
                        "pais" => "ISLA DE MAN"], 
                    [
                        "code" => 174, 
                        "pais" => "GIBRALTAR"], 
                    [
                        "code" => 175, 
                        "pais" => "ISLAS DEL CANAL"], 
                    [
                        "code" => 176, 
                        "pais" => "JERSEY"], 
                    [
                        "code" => 177, 
                        "pais" => "ISLAS ALAND"], 
                    [
                        "code" => 436, 
                        "pais" => "TURQUIA"], 
                    [
                        "code" => 199, 
                        "pais" => "OTROS PAISES O TERRITORIOS DEL RESTO DE EUROPA"], 
                    [
                        "code" => 201, 
                        "pais" => "BURKINA FASO"], 
                    [
                        "code" => 202, 
                        "pais" => "ANGOLA"], 
                    [
                        "code" => 203, 
                        "pais" => "ARGELIA"], 
                    [
                        "code" => 204, 
                        "pais" => "BENIN"], 
                    [
                        "code" => 205, 
                        "pais" => "BOTSWANA"], 
                    [
                        "code" => 206, 
                        "pais" => "BURUNDI"], 
                    [
                        "code" => 207, 
                        "pais" => "CABO VERDE"], 
                    [
                        "code" => 208, 
                        "pais" => "CAMERUN"], 
                    [
                        "code" => 209, 
                        "pais" => "COMORES"], 
                    [
                        "code" => 210, 
                        "pais" => "CONGO"], 
                    [
                        "code" => 211, 
                        "pais" => "COSTA DE MARFIL"], 
                    [
                        "code" => 212, 
                        "pais" => "DJIBOUTI"], 
                    [
                        "code" => 213, 
                        "pais" => "EGIPTO"], 
                    [
                        "code" => 214, 
                        "pais" => "ETIOPIA"], 
                    [
                        "code" => 215, 
                        "pais" => "GABON"], 
                    [
                        "code" => 216, 
                        "pais" => "GAMBIA"], 
                    [
                        "code" => 217, 
                        "pais" => "GHANA"], 
                    [
                        "code" => 218, 
                        "pais" => "GUINEA"], 
                    [
                        "code" => 219, 
                        "pais" => "GUINEA-BISSAU"], 
                    [
                        "code" => 220, 
                        "pais" => "GUINEA ECUATORIAL"], 
                    [
                        "code" => 221, 
                        "pais" => "KENIA"], 
                    [
                        "code" => 222, 
                        "pais" => "LESOTHO"], 
                    [
                        "code" => 223, 
                        "pais" => "LIBERIA"], 
                    [
                        "code" => 224, 
                        "pais" => "LIBIA"], 
                    [
                        "code" => 225, 
                        "pais" => "MADAGASCAR"], 
                    [
                        "code" => 226, 
                        "pais" => "MALAWI"], 
                    [
                        "code" => 227, 
                        "pais" => "MALI"], 
                    [
                        "code" => 228, 
                        "pais" => "MARRUECOS"], 
                    [
                        "code" => 229, 
                        "pais" => "MAURICIO"], 
                    [
                        "code" => 230, 
                        "pais" => "MAURITANIA"], 
                    [
                        "code" => 231, 
                        "pais" => "MOZAMBIQUE"], 
                    [
                        "code" => 232, 
                        "pais" => "NAMIBIA"], 
                    [
                        "code" => 233, 
                        "pais" => "NIGER"], 
                    [
                        "code" => 234, 
                        "pais" => "NIGERIA"], 
                    [
                        "code" => 235, 
                        "pais" => "REPUBLICA CENTROAFRICANA"], 
                    [
                        "code" => 236, 
                        "pais" => "SUDAFRICA"], 
                    [
                        "code" => 237, 
                        "pais" => "RUANDA"], 
                    [
                        "code" => 238, 
                        "pais" => "SANTO TOME Y PRINCIPE"], 
                    [
                        "code" => 239, 
                        "pais" => "SENEGAL"], 
                    [
                        "code" => 240, 
                        "pais" => "SEYCHELLES"], 
                    [
                        "code" => 241, 
                        "pais" => "SIERRA LEONA"], 
                    [
                        "code" => 242, 
                        "pais" => "SOMALIA"], 
                    [
                        "code" => 243, 
                        "pais" => "SUDAN"], 
                    [
                        "code" => 244, 
                        "pais" => "SWAZILANDIA"], 
                    [
                        "code" => 245, 
                        "pais" => "TANZANIA"], 
                    [
                        "code" => 246, 
                        "pais" => "CHAD"], 
                    [
                        "code" => 247, 
                        "pais" => "TOGO"], 
                    [
                        "code" => 248, 
                        "pais" => "TUNEZ"], 
                    [
                        "code" => 249, 
                        "pais" => "UGANDA"], 
                    [
                        "code" => 250, 
                        "pais" => "REP.DEMOCRATICA DEL CONGO"], 
                    [
                        "code" => 251, 
                        "pais" => "ZAMBIA"], 
                    [
                        "code" => 252, 
                        "pais" => "ZIMBABWE"], 
                    [
                        "code" => 253, 
                        "pais" => "ERITREA"], 
                    [
                        "code" => 260, 
                        "pais" => "SANTA HELENA"], 
                    [
                        "code" => 261, 
                        "pais" => "REUNION"], 
                    [
                        "code" => 262, 
                        "pais" => "MAYOTTE"], 
                    [
                        "code" => 263, 
                        "pais" => "SAHARA OCCIDENTAL"], 
                    [
                        "code" => 299, 
                        "pais" => "OTROS PAISES O TERRITORIOS DE AFRICA"], 
                    [
                        "code" => 301, 
                        "pais" => "CANADA"], 
                    [
                        "code" => 370, 
                        "pais" => "SAN PEDRO Y MIQUELON "], 
                    [
                        "code" => 371, 
                        "pais" => "GROENLANDIA"], 
                    [
                        "code" => 396, 
                        "pais" => "OTROS PAISES  O TERRITORIOS DE AMERICA DEL NORTE"], 
                    [
                        "code" => 310, 
                        "pais" => "ANTIGUA Y BARBUDA"], 
                    [
                        "code" => 311, 
                        "pais" => "BAHAMAS"], 
                    [
                        "code" => 312, 
                        "pais" => "BARBADOS"], 
                    [
                        "code" => 313, 
                        "pais" => "BELICE"], 
                    [
                        "code" => 314, 
                        "pais" => "COSTA RICA"], 
                    [
                        "code" => 315, 
                        "pais" => "CUBA"], 
                    [
                        "code" => 316, 
                        "pais" => "DOMINICA"], 
                    [
                        "code" => 317, 
                        "pais" => "EL SALVADOR"], 
                    [
                        "code" => 318, 
                        "pais" => "GRANADA"], 
                    [
                        "code" => 319, 
                        "pais" => "GUATEMALA"], 
                    [
                        "code" => 320, 
                        "pais" => "HAITI"], 
                    [
                        "code" => 321, 
                        "pais" => "HONDURAS"], 
                    [
                        "code" => 322, 
                        "pais" => "JAMAICA"], 
                    [
                        "code" => 323, 
                        "pais" => "NICARAGUA"], 
                    [
                        "code" => 324, 
                        "pais" => "PANAMA"], 
                    [
                        "code" => 325, 
                        "pais" => "SAN VICENTE Y LAS GRANADINAS"], 
                    [
                        "code" => 326, 
                        "pais" => "REPUBLICA DOMINICANA"], 
                    [
                        "code" => 327, 
                        "pais" => "TRINIDAD Y TOBAGO"], 
                    [
                        "code" => 328, 
                        "pais" => "SANTA LUCIA"], 
                    [
                        "code" => 329, 
                        "pais" => "SAN CRISTOBAL Y NIEVES"], 
                    [
                        "code" => 380, 
                        "pais" => "ISLAS CAIMÁN"], 
                    [
                        "code" => 381, 
                        "pais" => "ISLAS TURCAS Y CAICOS"], 
                    [
                        "code" => 382, 
                        "pais" => "ISLAS VÍRGENES DE LOS ESTADOS UNIDOS"], 
                    [
                        "code" => 383, 
                        "pais" => "GUADALUPE"], 
                    [
                        "code" => 384, 
                        "pais" => "ANTILLAS HOLANDESAS"], 
                    [
                        "code" => 385, 
                        "pais" => "SAN MARTIN (PARTE FRANCESA)"], 
                    [
                        "code" => 386, 
                        "pais" => "ARUBA"], 
                    [
                        "code" => 387, 
                        "pais" => "MONTSERRAT"], 
                    [
                        "code" => 388, 
                        "pais" => "ANGUILLA"], 
                    [
                        "code" => 389, 
                        "pais" => "SAN BARTOLOME"], 
                    [
                        "code" => 390, 
                        "pais" => "MARTINICA"], 
                    [
                        "code" => 391, 
                        "pais" => "PUERTO RICO"], 
                    [
                        "code" => 392, 
                        "pais" => "BERMUDAS"], 
                    [
                        "code" => 393, 
                        "pais" => "ISLAS VIRGENES BRITANICAS"], 
                    [
                        "code" => 398, 
                        "pais" => "OTROS PAISES O TERRITORIOS DEL CARIBE Y AMERICA CENTRAL"], 
                    [
                        "code" => 340, 
                        "pais" => "ARGENTINA"], 
                    [
                        "code" => 341, 
                        "pais" => "BOLIVIA"], 
                    [
                        "code" => 342, 
                        "pais" => "BRASIL"], 
                    [
                        "code" => 343, 
                        "pais" => "COLOMBIA"], 
                    [
                        "code" => 344, 
                        "pais" => "CHILE"], 
                    [
                        "code" => 345, 
                        "pais" => "ECUADOR"], 
                    [
                        "code" => 346, 
                        "pais" => "GUYANA"], 
                    [
                        "code" => 347, 
                        "pais" => "PARAGUAY"], 
                    [
                        "code" => 348, 
                        "pais" => "PERU"], 
                    [
                        "code" => 349, 
                        "pais" => "SURINAM"], 
                    [
                        "code" => 350, 
                        "pais" => "URUGUAY"], 
                    [
                        "code" => 351, 
                        "pais" => "VENEZUELA"], 
                    [
                        "code" => 394, 
                        "pais" => "GUAYANA FRANCESA"], 
                    [
                        "code" => 395, 
                        "pais" => "ISLAS MALVINAS"], 
                    [
                        "code" => 399, 
                        "pais" => "OTROS PAISES O TERRITORIOS  DE SUDAMERICA"], 
                    [
                        "code" => 401, 
                        "pais" => "AFGANISTAN"], 
                    [
                        "code" => 402, 
                        "pais" => "ARABIA SAUDI"], 
                    [
                        "code" => 403, 
                        "pais" => "BAHREIN"], 
                    [
                        "code" => 404, 
                        "pais" => "BANGLADESH"], 
                    [
                        "code" => 405, 
                        "pais" => "MYANMAR"], 
                    [
                        "code" => 407, 
                        "pais" => "CHINA"], 
                    [
                        "code" => 408, 
                        "pais" => "EMIRATOS ARABES UNIDOS"], 
                    [
                        "code" => 409, 
                        "pais" => "FILIPINAS"], 
                    [
                        "code" => 410, 
                        "pais" => "INDIA"], 
                    [
                        "code" => 411, 
                        "pais" => "INDONESIA"], 
                    [
                        "code" => 412, 
                        "pais" => "IRAQ"], 
                    [
                        "code" => 413, 
                        "pais" => "IRAN"], 
                    [
                        "code" => 414, 
                        "pais" => "ISRAEL"], 
                    [
                        "code" => 415, 
                        "pais" => "JAPON"], 
                    [
                        "code" => 416, 
                        "pais" => "JORDANIA"], 
                    [
                        "code" => 417, 
                        "pais" => "CAMBOYA"], 
                    [
                        "code" => 418, 
                        "pais" => "KUWAIT"], 
                    [
                        "code" => 419, 
                        "pais" => "LAOS"], 
                    [
                        "code" => 420, 
                        "pais" => "LIBANO"], 
                    [
                        "code" => 421, 
                        "pais" => "MALASIA"], 
                    [
                        "code" => 422, 
                        "pais" => "MALDIVAS"], 
                    [
                        "code" => 423, 
                        "pais" => "MONGOLIA"], 
                    [
                        "code" => 424, 
                        "pais" => "NEPAL"], 
                    [
                        "code" => 425, 
                        "pais" => "OMAN"], 
                    [
                        "code" => 426, 
                        "pais" => "PAKISTAN"], 
                    [
                        "code" => 427, 
                        "pais" => "QATAR"], 
                    [
                        "code" => 430, 
                        "pais" => "COREA"], 
                    [
                        "code" => 431, 
                        "pais" => "COREA DEL NORTE "], 
                    [
                        "code" => 432, 
                        "pais" => "SINGAPUR"], 
                    [
                        "code" => 433, 
                        "pais" => "SIRIA"], 
                    [
                        "code" => 434, 
                        "pais" => "SRI LANKA"], 
                    [
                        "code" => 435, 
                        "pais" => "TAILANDIA"], 
                    [
                        "code" => 437, 
                        "pais" => "VIETNAM"], 
                    [
                        "code" => 439, 
                        "pais" => "BRUNEI"], 
                    [
                        "code" => 440, 
                        "pais" => "ISLAS MARSHALL"], 
                    [
                        "code" => 441, 
                        "pais" => "YEMEN"], 
                    [
                        "code" => 442, 
                        "pais" => "AZERBAIYAN"], 
                    [
                        "code" => 443, 
                        "pais" => "KAZAJSTAN"], 
                    [
                        "code" => 444, 
                        "pais" => "KIRGUISTAN"], 
                    [
                        "code" => 445, 
                        "pais" => "TADYIKISTAN"], 
                    [
                        "code" => 446, 
                        "pais" => "TURKMENISTAN"], 
                    [
                        "code" => 447, 
                        "pais" => "UZBEKISTAN"], 
                    [
                        "code" => 448, 
                        "pais" => "ISLAS MARIANAS DEL NORTE"], 
                    [
                        "code" => 449, 
                        "pais" => "PALESTINA"], 
                    [
                        "code" => 450, 
                        "pais" => "HONG KONG"], 
                    [
                        "code" => 453, 
                        "pais" => "BHUTÁN"], 
                    [
                        "code" => 454, 
                        "pais" => "GUAM"], 
                    [
                        "code" => 455, 
                        "pais" => "MACAO"], 
                    [
                        "code" => 499, 
                        "pais" => "OTROS PAISES O TERRITORIOS DE ASIA"], 
                    [
                        "code" => 501, 
                        "pais" => "AUSTRALIA"], 
                    [
                        "code" => 502, 
                        "pais" => "FIJI"], 
                    [
                        "code" => 504, 
                        "pais" => "NUEVA ZELANDA"], 
                    [
                        "code" => 505, 
                        "pais" => "PAPUA NUEVA GUINEA"], 
                    [
                        "code" => 506, 
                        "pais" => "ISLAS SALOMON"], 
                    [
                        "code" => 507, 
                        "pais" => "SAMOA"], 
                    [
                        "code" => 508, 
                        "pais" => "TONGA"], 
                    [
                        "code" => 509, 
                        "pais" => "VANUATU"], 
                    [
                        "code" => 511, 
                        "pais" => "MICRONESIA"], 
                    [
                        "code" => 512, 
                        "pais" => "TUVALU"], 
                    [
                        "code" => 513, 
                        "pais" => "ISLAS COOK"], 
                    [
                        "code" => 515, 
                        "pais" => "NAURU"], 
                    [
                        "code" => 516, 
                        "pais" => "PALAOS"], 
                    [
                        "code" => 517, 
                        "pais" => "TIMOR ORIENTAL"], 
                    [
                        "code" => 520, 
                        "pais" => "POLINESIA FRANCESA"], 
                    [
                        "code" => 521, 
                        "pais" => "ISLA NORFOLK"], 
                    [
                        "code" => 522, 
                        "pais" => "KIRIBATI"], 
                    [
                        "code" => 523, 
                        "pais" => "NIUE"], 
                    [
                        "code" => 524, 
                        "pais" => "ISLAS PITCAIRN"], 
                    [
                        "code" => 525, 
                        "pais" => "TOKELAU"], 
                    [
                        "code" => 526, 
                        "pais" => "NUEVA CALEDONIA"], 
                    [
                        "code" => 527, 
                        "pais" => "WALLIS Y FORTUNA"], 
                    [
                        "code" => 528, 
                        "pais" => "SAMOA AMERICANA"], 
                    [
                        "code" => 599, 
                        "pais" => "OTROS PAISES O TERRITORIOS DE OCEANIA"]
          ]);
    }
}
