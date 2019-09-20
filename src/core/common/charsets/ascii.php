<?php
namespace core\common\charsets;
use core\common\enum;
use core\backend\database\dataset;

/**
 * ascii short summary.
 *
 * ascii description.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class ascii extends enum
{
    protected $array = array(
        array("url"=>'%00',"double_url"=>'%2500',"hex"=>'00',"oct"=>'000',"html"=>'&#0',"char"=>'\x00',"char_code"=>'0'), // shortcut \0
        array("url"=>'%01',"double_url"=>'%2501',"hex"=>'01',"oct"=>'001',"html"=>'&#1',"char"=>'\x01',"char_code"=>'1'),
        array("url"=>'%02',"double_url"=>'%2502',"hex"=>'02',"oct"=>'002',"html"=>'&#2',"char"=>'\x02',"char_code"=>'2'),
        array("url"=>'%03',"double_url"=>'%2503',"hex"=>'03',"oct"=>'003',"html"=>'&#3',"char"=>'\x03',"char_code"=>'3'),
        array("url"=>'%04',"double_url"=>'%2504',"hex"=>'04',"oct"=>'004',"html"=>'&#4',"char"=>'\x04',"char_code"=>'4'),
        array("url"=>'%05',"double_url"=>'%2505',"hex"=>'05',"oct"=>'005',"html"=>'&#5',"char"=>'\x05',"char_code"=>'5'),
        array("url"=>'%06',"double_url"=>'%2506',"hex"=>'06',"oct"=>'006',"html"=>'&#6',"char"=>'\x06',"char_code"=>'6'),
        array("url"=>'%07',"double_url"=>'%2507',"hex"=>'07',"oct"=>'007',"html"=>'&#7',"char"=>'\x07',"char_code"=>'7'), // shortcut \a
        array("url"=>'%08',"double_url"=>'%2508',"hex"=>'08',"oct"=>'010',"html"=>'&#8',"char"=>'\x08',"char_code"=>'8'),
        array("url"=>'%09',"double_url"=>'%2509',"hex"=>'09',"oct"=>'011',"html"=>'&#9',"char"=>'\x09',"char_code"=>'9'), // shortcut \t
        array("url"=>'%0A',"double_url"=>'%250A',"hex"=>'0A',"oct"=>'012',"html"=>'&#10',"char"=>'\x0A',"char_code"=>'10'), // shortcut \n
        array("url"=>'%0B',"double_url"=>'%250B',"hex"=>'0B',"oct"=>'013',"html"=>'&#11',"char"=>'\x0B',"char_code"=>'11'), // shortcut \v
        array("url"=>'%0C',"double_url"=>'%250C',"hex"=>'0C',"oct"=>'014',"html"=>'&#12',"char"=>'\x0C',"char_code"=>'12'), // shortcut \f
        array("url"=>'%0D',"double_url"=>'%250D',"hex"=>'0D',"oct"=>'015',"html"=>'&#13',"char"=>'\x0D',"char_code"=>'13'), // shortcut \r
        array("url"=>'%0E',"double_url"=>'%250E',"hex"=>'0E',"oct"=>'016',"html"=>'&#14',"char"=>'\x1E',"char_code"=>'14'),
        array("url"=>'%0F',"double_url"=>'%250F',"hex"=>'0F',"oct"=>'017',"html"=>'&#15',"char"=>'\x1F',"char_code"=>'15'),
        array("url"=>'%10',"double_url"=>'%2510',"hex"=>'10',"oct"=>'020',"html"=>'&#16',"char"=>'\x10',"char_code"=>'16'),
        array("url"=>'%11',"double_url"=>'%2511',"hex"=>'11',"oct"=>'021',"html"=>'&#17',"char"=>'\x11',"char_code"=>'17'),
        array("url"=>'%12',"double_url"=>'%2512',"hex"=>'12',"oct"=>'022',"html"=>'&#18',"char"=>'\x12',"char_code"=>'18'),
        array("url"=>'%13',"double_url"=>'%2513',"hex"=>'13',"oct"=>'023',"html"=>'&#19',"char"=>'\x13',"char_code"=>'19'),
        array("url"=>'%14',"double_url"=>'%2514',"hex"=>'14',"oct"=>'024',"html"=>'&#20',"char"=>'\x14',"char_code"=>'20'),
        array("url"=>'%15',"double_url"=>'%2515',"hex"=>'15',"oct"=>'025',"html"=>'&#21',"char"=>'\x15',"char_code"=>'21'),
        array("url"=>'%16',"double_url"=>'%2516',"hex"=>'16',"oct"=>'026',"html"=>'&#22',"char"=>'\x16',"char_code"=>'22'),
        array("url"=>'%17',"double_url"=>'%2517',"hex"=>'17',"oct"=>'027',"html"=>'&#23',"char"=>'\x17',"char_code"=>'23'),
        array("url"=>'%18',"double_url"=>'%2518',"hex"=>'18',"oct"=>'030',"html"=>'&#24',"char"=>'\x18',"char_code"=>'24'),
        array("url"=>'%19',"double_url"=>'%2519',"hex"=>'19',"oct"=>'031',"html"=>'&#25',"char"=>'\x19',"char_code"=>'25'),
        array("url"=>'%1A',"double_url"=>'%251A',"hex"=>'1A',"oct"=>'032',"html"=>'&#26',"char"=>'\x1A',"char_code"=>'26'),
        array("url"=>'%1B',"double_url"=>'%251B',"hex"=>'1B',"oct"=>'033',"html"=>'&#27',"char"=>'\x1B',"char_code"=>'27'), // shortcut \e
        array("url"=>'%1C',"double_url"=>'%251C',"hex"=>'1C',"oct"=>'034',"html"=>'&#28',"char"=>'\x1C',"char_code"=>'28'),
        array("url"=>'%1D',"double_url"=>'%251D',"hex"=>'1D',"oct"=>'035',"html"=>'&#29',"char"=>'\x1D',"char_code"=>'29'),
        array("url"=>'%1E',"double_url"=>'%251E',"hex"=>'1E',"oct"=>'036',"html"=>'&#30',"char"=>'\x1E',"char_code"=>'30'),
        array("url"=>'%1F',"double_url"=>'%251F',"hex"=>'1F',"oct"=>'037',"html"=>'&#31',"char"=>'\x1F',"char_code"=>'31'),
        array("url"=>'%20',"double_url"=>'%2520',"hex"=>'20',"oct"=>'040',"html"=>'&#32;',"char"=>' ',"char_code"=>'32'),
        array("url"=>'%21',"double_url"=>'%2521',"hex"=>'21',"oct"=>'041',"html"=>'&#33;',"char"=>'!',"char_code"=>'33'),
        array("url"=>'%22',"double_url"=>'%2522',"hex"=>'22',"oct"=>'042',"html"=>'&#34;',"char"=>'"',"char_code"=>'34'),
        array("url"=>'%23',"double_url"=>'%2523',"hex"=>'23',"oct"=>'043',"html"=>'&#35;',"char"=>'#',"char_code"=>'35'),
        array("url"=>'%24',"double_url"=>'%2524',"hex"=>'24',"oct"=>'044',"html"=>'&#36;',"char"=>'$',"char_code"=>'36'),
        array("url"=>'%25',"double_url"=>'%2525',"hex"=>'25',"oct"=>'045',"html"=>'&#37;',"char"=>'%',"char_code"=>'37'),
        array("url"=>'%26',"double_url"=>'%2526',"hex"=>'26',"oct"=>'046',"html"=>'&#38;',"char"=>'&',"char_code"=>'38'),
        array("url"=>'%27',"double_url"=>'%2527',"hex"=>'27',"oct"=>'047',"html"=>'&#39;',"char"=>"'","char_code"=>'39'),
        array("url"=>'%28',"double_url"=>'%2528',"hex"=>'28',"oct"=>'050',"html"=>'&#40;',"char"=>'(',"char_code"=>'40'),
        array("url"=>'%29',"double_url"=>'%2529',"hex"=>'29',"oct"=>'051',"html"=>'&#41;',"char"=>')',"char_code"=>'41'),
        array("url"=>'%2A',"double_url"=>'%252A',"hex"=>'2A',"oct"=>'052',"html"=>'&#42;',"char"=>'*',"char_code"=>'42'),
        array("url"=>'%2B',"double_url"=>'%252B',"hex"=>'2B',"oct"=>'053',"html"=>'&#43;',"char"=>'+',"char_code"=>'43'),
        array("url"=>'%2C',"double_url"=>'%252C',"hex"=>'2C',"oct"=>'054',"html"=>'&#44;',"char"=>',',"char_code"=>'44'),
        array("url"=>'%2D',"double_url"=>'%252D',"hex"=>'2D',"oct"=>'055',"html"=>'&#45;',"char"=>'-',"char_code"=>'45'),
        array("url"=>'%2E',"double_url"=>'%252E',"hex"=>'2E',"oct"=>'056',"html"=>'&#46;',"char"=>'.',"char_code"=>'46'),
        array("url"=>'%2F',"double_url"=>'%252F',"hex"=>'2F',"oct"=>'057',"html"=>'&#47;',"char"=>'/',"char_code"=>'47'),
        array("url"=>'%30',"double_url"=>'%2530',"hex"=>'30',"oct"=>'060',"html"=>'&#48;',"char"=>'0',"char_code"=>'48'),
        array("url"=>'%31',"double_url"=>'%2531',"hex"=>'31',"oct"=>'061',"html"=>'&#49;',"char"=>'1',"char_code"=>'49'),
        array("url"=>'%32',"double_url"=>'%2532',"hex"=>'32',"oct"=>'062',"html"=>'&#50;',"char"=>'2',"char_code"=>'50'),
        array("url"=>'%33',"double_url"=>'%2533',"hex"=>'33',"oct"=>'063',"html"=>'&#51;',"char"=>'3',"char_code"=>'51'),
        array("url"=>'%34',"double_url"=>'%2534',"hex"=>'34',"oct"=>'064',"html"=>'&#52;',"char"=>'4',"char_code"=>'52'),
        array("url"=>'%35',"double_url"=>'%2535',"hex"=>'35',"oct"=>'065',"html"=>'&#53;',"char"=>'5',"char_code"=>'53'),
        array("url"=>'%36',"double_url"=>'%2536',"hex"=>'36',"oct"=>'066',"html"=>'&#54;',"char"=>'6',"char_code"=>'54'),
        array("url"=>'%37',"double_url"=>'%2537',"hex"=>'37',"oct"=>'067',"html"=>'&#55;',"char"=>'7',"char_code"=>'55'),
        array("url"=>'%38',"double_url"=>'%2538',"hex"=>'38',"oct"=>'070',"html"=>'&#56;',"char"=>'8',"char_code"=>'56'),
        array("url"=>'%39',"double_url"=>'%2539',"hex"=>'39',"oct"=>'071',"html"=>'&#57;',"char"=>'9',"char_code"=>'57'),
        array("url"=>'%3A',"double_url"=>'%253A',"hex"=>'3A',"oct"=>'072',"html"=>'&#58;',"char"=>':',"char_code"=>'58'),
        array("url"=>'%3B',"double_url"=>'%253B',"hex"=>'3B',"oct"=>'073',"html"=>'&#59;',"char"=>';',"char_code"=>'59'),
        array("url"=>'%3C',"double_url"=>'%253C',"hex"=>'3C',"oct"=>'074',"html"=>'&#60;',"char"=>'<',"char_code"=>'60'),
        array("url"=>'%3D',"double_url"=>'%253D',"hex"=>'3D',"oct"=>'075',"html"=>'&#61;',"char"=>'=',"char_code"=>'61'),
        array("url"=>'%3E',"double_url"=>'%253E',"hex"=>'3E',"oct"=>'076',"html"=>'&#62;',"char"=>'>',"char_code"=>'62'),
        array("url"=>'%3F',"double_url"=>'%253F',"hex"=>'3F',"oct"=>'077',"html"=>'&#63;',"char"=>'?',"char_code"=>'63'),
        array("url"=>'%40',"double_url"=>'%2540',"hex"=>'40',"oct"=>'100',"html"=>'&#64;',"char"=>'@',"char_code"=>'64'),
        array("url"=>'%41',"double_url"=>'%2541',"hex"=>'41',"oct"=>'101',"html"=>'&#65;',"char"=>'A',"char_code"=>'65'),
        array("url"=>'%42',"double_url"=>'%2542',"hex"=>'42',"oct"=>'102',"html"=>'&#66;',"char"=>'B',"char_code"=>'66'),
        array("url"=>'%43',"double_url"=>'%2543',"hex"=>'43',"oct"=>'103',"html"=>'&#67;',"char"=>'C',"char_code"=>'67'),
        array("url"=>'%44',"double_url"=>'%2544',"hex"=>'44',"oct"=>'104',"html"=>'&#68;',"char"=>'D',"char_code"=>'68'),
        array("url"=>'%45',"double_url"=>'%2545',"hex"=>'45',"oct"=>'105',"html"=>'&#69;',"char"=>'E',"char_code"=>'69'),
        array("url"=>'%46',"double_url"=>'%2546',"hex"=>'46',"oct"=>'106',"html"=>'&#70;',"char"=>'F',"char_code"=>'70'),
        array("url"=>'%47',"double_url"=>'%2547',"hex"=>'47',"oct"=>'107',"html"=>'&#71;',"char"=>'G',"char_code"=>'71'),
        array("url"=>'%48',"double_url"=>'%2548',"hex"=>'48',"oct"=>'110',"html"=>'&#72;',"char"=>'H',"char_code"=>'72'),
        array("url"=>'%49',"double_url"=>'%2549',"hex"=>'49',"oct"=>'111',"html"=>'&#73;',"char"=>'I',"char_code"=>'73'),
        array("url"=>'%4A',"double_url"=>'%254A',"hex"=>'4A',"oct"=>'112',"html"=>'&#74;',"char"=>'J',"char_code"=>'74'),
        array("url"=>'%4B',"double_url"=>'%254B',"hex"=>'4B',"oct"=>'113',"html"=>'&#75;',"char"=>'K',"char_code"=>'75'),
        array("url"=>'%4C',"double_url"=>'%254C',"hex"=>'4C',"oct"=>'114',"html"=>'&#76;',"char"=>'L',"char_code"=>'76'),
        array("url"=>'%4D',"double_url"=>'%254D',"hex"=>'4D',"oct"=>'115',"html"=>'&#77;',"char"=>'M',"char_code"=>'77'),
        array("url"=>'%4E',"double_url"=>'%254E',"hex"=>'4E',"oct"=>'116',"html"=>'&#78;',"char"=>'N',"char_code"=>'78'),
        array("url"=>'%4F',"double_url"=>'%254F',"hex"=>'4F',"oct"=>'117',"html"=>'&#79;',"char"=>'O',"char_code"=>'79'),
        array("url"=>'%50',"double_url"=>'%2550',"hex"=>'50',"oct"=>'120',"html"=>'&#80;',"char"=>'P',"char_code"=>'80'),
        array("url"=>'%51',"double_url"=>'%2551',"hex"=>'51',"oct"=>'121',"html"=>'&#81;',"char"=>'Q',"char_code"=>'81'),
        array("url"=>'%52',"double_url"=>'%2552',"hex"=>'52',"oct"=>'122',"html"=>'&#82;',"char"=>'R',"char_code"=>'82'),
        array("url"=>'%53',"double_url"=>'%2553',"hex"=>'53',"oct"=>'123',"html"=>'&#83;',"char"=>'S',"char_code"=>'83'),
        array("url"=>'%54',"double_url"=>'%2554',"hex"=>'54',"oct"=>'124',"html"=>'&#84;',"char"=>'T',"char_code"=>'84'),
        array("url"=>'%55',"double_url"=>'%2555',"hex"=>'55',"oct"=>'125',"html"=>'&#85;',"char"=>'U',"char_code"=>'85'),
        array("url"=>'%56',"double_url"=>'%2556',"hex"=>'56',"oct"=>'126',"html"=>'&#86;',"char"=>'V',"char_code"=>'86'),
        array("url"=>'%57',"double_url"=>'%2557',"hex"=>'57',"oct"=>'127',"html"=>'&#87;',"char"=>'W',"char_code"=>'87'),
        array("url"=>'%58',"double_url"=>'%2558',"hex"=>'58',"oct"=>'130',"html"=>'&#88;',"char"=>'X',"char_code"=>'88'),
        array("url"=>'%59',"double_url"=>'%2559',"hex"=>'59',"oct"=>'131',"html"=>'&#89;',"char"=>'Y',"char_code"=>'89'),
        array("url"=>'%5A',"double_url"=>'%255A',"hex"=>'5A',"oct"=>'132',"html"=>'&#90;',"char"=>'Z',"char_code"=>'90'),
        array("url"=>'%5B',"double_url"=>'%255B',"hex"=>'5B',"oct"=>'133',"html"=>'&#91;',"char"=>'[',"char_code"=>'91'),
        array("url"=>'%5C',"double_url"=>'%255C',"hex"=>'5C',"oct"=>'134',"html"=>'&#92;',"char"=>'\\',"char_code"=>'92'),
        array("url"=>'%5D',"double_url"=>'%255D',"hex"=>'5D',"oct"=>'135',"html"=>'&#93;',"char"=>']',"char_code"=>'93'),
        array("url"=>'%5E',"double_url"=>'%255E',"hex"=>'5E',"oct"=>'136',"html"=>'&#94;',"char"=>'^',"char_code"=>'94'),
        array("url"=>'%5F',"double_url"=>'%255F',"hex"=>'5F',"oct"=>'137',"html"=>'&#95;',"char"=>'_',"char_code"=>'95'),
        array("url"=>'%60',"double_url"=>'%2560',"hex"=>'60',"oct"=>'140',"html"=>'&#96;',"char"=>'`',"char_code"=>'96'),
        array("url"=>'%61',"double_url"=>'%2561',"hex"=>'61',"oct"=>'141',"html"=>'&#97;',"char"=>'a',"char_code"=>'97'),
        array("url"=>'%62',"double_url"=>'%2562',"hex"=>'62',"oct"=>'142',"html"=>'&#98;',"char"=>'b',"char_code"=>'98'),
        array("url"=>'%63',"double_url"=>'%2563',"hex"=>'63',"oct"=>'143',"html"=>'&#99;',"char"=>'c',"char_code"=>'99'),
        array("url"=>'%64',"double_url"=>'%2564',"hex"=>'64',"oct"=>'144',"html"=>'&#100;',"char"=>'d',"char_code"=>'100'),
        array("url"=>'%65',"double_url"=>'%2565',"hex"=>'65',"oct"=>'145',"html"=>'&#101;',"char"=>'e',"char_code"=>'101'),
        array("url"=>'%66',"double_url"=>'%2566',"hex"=>'66',"oct"=>'146',"html"=>'&#102;',"char"=>'f',"char_code"=>'102'),
        array("url"=>'%67',"double_url"=>'%2567',"hex"=>'67',"oct"=>'147',"html"=>'&#103;',"char"=>'g',"char_code"=>'103'),
        array("url"=>'%68',"double_url"=>'%2568',"hex"=>'68',"oct"=>'150',"html"=>'&#104;',"char"=>'h',"char_code"=>'104'),
        array("url"=>'%69',"double_url"=>'%2569',"hex"=>'69',"oct"=>'151',"html"=>'&#105;',"char"=>'i',"char_code"=>'105'),
        array("url"=>'%6A',"double_url"=>'%256A',"hex"=>'6A',"oct"=>'152',"html"=>'&#106;',"char"=>'j',"char_code"=>'106'),
        array("url"=>'%6B',"double_url"=>'%256B',"hex"=>'6B',"oct"=>'153',"html"=>'&#107;',"char"=>'k',"char_code"=>'107'),
        array("url"=>'%6C',"double_url"=>'%256C',"hex"=>'6C',"oct"=>'154',"html"=>'&#108;',"char"=>'l',"char_code"=>'108'),
        array("url"=>'%6D',"double_url"=>'%256D',"hex"=>'6D',"oct"=>'155',"html"=>'&#109;',"char"=>'m',"char_code"=>'109'),
        array("url"=>'%6E',"double_url"=>'%256E',"hex"=>'6E',"oct"=>'156',"html"=>'&#110;',"char"=>'n',"char_code"=>'110'),
        array("url"=>'%6F',"double_url"=>'%256F',"hex"=>'6F',"oct"=>'157',"html"=>'&#111;',"char"=>'o',"char_code"=>'111'),
        array("url"=>'%70',"double_url"=>'%2570',"hex"=>'70',"oct"=>'160',"html"=>'&#112;',"char"=>'p',"char_code"=>'112'),
        array("url"=>'%71',"double_url"=>'%2571',"hex"=>'71',"oct"=>'161',"html"=>'&#113;',"char"=>'q',"char_code"=>'113'),
        array("url"=>'%72',"double_url"=>'%2572',"hex"=>'72',"oct"=>'162',"html"=>'&#114;',"char"=>'r',"char_code"=>'114'),
        array("url"=>'%73',"double_url"=>'%2573',"hex"=>'73',"oct"=>'163',"html"=>'&#115;',"char"=>'s',"char_code"=>'115'),
        array("url"=>'%74',"double_url"=>'%2574',"hex"=>'74',"oct"=>'164',"html"=>'&#116;',"char"=>'t',"char_code"=>'116'),
        array("url"=>'%75',"double_url"=>'%2575',"hex"=>'75',"oct"=>'165',"html"=>'&#117;',"char"=>'u',"char_code"=>'117'),
        array("url"=>'%76',"double_url"=>'%2576',"hex"=>'76',"oct"=>'166',"html"=>'&#118;',"char"=>'v',"char_code"=>'118'),
        array("url"=>'%77',"double_url"=>'%2577',"hex"=>'77',"oct"=>'167',"html"=>'&#119;',"char"=>'w',"char_code"=>'119'),
        array("url"=>'%78',"double_url"=>'%2578',"hex"=>'78',"oct"=>'170',"html"=>'&#120;',"char"=>'x',"char_code"=>'120'),
        array("url"=>'%79',"double_url"=>'%2579',"hex"=>'79',"oct"=>'171',"html"=>'&#121;',"char"=>'y',"char_code"=>'121'),
        array("url"=>'%7A',"double_url"=>'%257A',"hex"=>'7A',"oct"=>'172',"html"=>'&#122;',"char"=>'z',"char_code"=>'122'),
        array("url"=>'%7B',"double_url"=>'%257B',"hex"=>'7B',"oct"=>'173',"html"=>'&#123;',"char"=>'{',"char_code"=>'123'),
        array("url"=>'%7C',"double_url"=>'%257C',"hex"=>'7C',"oct"=>'174',"html"=>'&#124;',"char"=>'|',"char_code"=>'124'),
        array("url"=>'%7D',"double_url"=>'%257D',"hex"=>'7D',"oct"=>'175',"html"=>'&#125;',"char"=>'}',"char_code"=>'125'),
        array("url"=>'%7E',"double_url"=>'%257E',"hex"=>'7E',"oct"=>'176',"html"=>'&#126;',"char"=>'~',"char_code"=>'126'),
        array("url"=>'%7F',"double_url"=>'%257F',"hex"=>'7F',"oct"=>'177',"html"=>'&#127;',"char"=>'\x7F',"char_code"=>'127')
    );

    public function offsetGet($offset)
    {
        return isset($this->array[$offset]) ? new ascii_char($this->array[$offset]) : null;
    }

}

class ascii_char extends dataset
{

    protected $url;

    protected $double_url;

    protected $hex;

    protected $oct;

    protected $html;

    protected $char;

    protected $char_code;
    
    public function get_url()
    {
        return $this->url;
    }

    public function get_double_url()
    {
        return $this->url;
    }

    public function get_hex()
    {
        return $this->hex;
    }

    public function get_oct()
    {
        return $this->oct;
    }

    public function get_html()
    {
        return $this->char;
    }

    public function get_char()
    {
        return $this->char;
    }

    public function get_char_code()
    {
        return $this->char_code;
    }

}
