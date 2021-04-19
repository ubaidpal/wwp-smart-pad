<?php
/**
 * Created by PhpStorm.
 * User: Ubaid
 * Date: 1-29-2019
 * File: Helpers
 * Time: 4:02 PM
 */
/*===============================================================================
 *
 *                  ***********      Global Helpers Functions       **********
 *
 * ==============================================================================*/

// Api route
// random USer Name Generate
function random_username($length = 10) {
    $characters       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[ rand(0, $charactersLength - 1) ];
    }
    return $randomString;
}
function random_password($length = 10) {
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[ rand(0, $charactersLength - 1) ];
    }
    return $randomString;
}
function setStringReturn($variable){
   return ($variable) ? $variable : 'not found';
}
// Dealer Status
function dealerStatus(){
    return array(
        [
            'id' => 1,
            'label' => 'Active',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="25px" height="25px" fill="green" viewBox="0 0 20 20">
                        <path d="M11 0h1v3l3 7v8a2 2 0 0 1-2 2H5c-1.1 0-2.31-.84-2.7-1.88L0 12v-2a2 2 0 0 1 2-2h7V2a2 2 0 0 1 2-2zm6 10h3v10h-3V10z"/>
                    </svg> '
        ],
        [
            'id' => 2,
            'label' => 'Limited',
            'icon' => '<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
                 width="25px" height="25px" fill="#DEB887" viewBox="0 0 200.000000 200.000000"
                 preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,200.000000) scale(0.100000,-0.100000)"
                    fill="#DEB887" stroke="none">
                        <path d="M710 1664 c-130 -34 -273 -125 -360 -229 -50 -61 -115 -189 -135
                        -270 -20 -77 -20 -253 0 -330 49 -192 184 -359 360 -446 109 -53 179 -69 305
                        -69 129 0 198 16 312 74 l87 43 3 -56 3 -56 255 0 255 0 0 195 0 195 -179 3
                        -179 2 7 53 c10 74 36 99 100 95 47 -3 52 -6 79 -46 23 -35 34 -43 55 -40 83
                        10 25 144 -74 170 l-44 12 0 71 c0 176 -93 364 -245 493 -60 52 -187 116 -270
                        137 -75 19 -261 19 -335 -1z m315 -89 c106 -27 183 -73 270 -160 118 -118 175
                        -247 175 -395 -1 -62 -2 -66 -33 -85 -54 -34 -77 -79 -77 -151 0 -36 -4 -64
                        -10 -64 -5 0 -10 12 -10 27 0 85 -99 181 -231 224 -66 21 -92 24 -234 24 -181
                        0 -242 -15 -342 -81 -81 -53 -107 -99 -113 -203 l-5 -86 -38 60 c-57 91 -87
                        197 -87 308 0 178 50 299 174 423 86 86 161 131 266 158 73 19 221 20 295 1z
                        m50 -688 c68 -22 136 -68 160 -109 15 -25 20 -56 23 -135 l4 -102 -63 -41
                        c-188 -121 -450 -121 -637 0 l-62 40 0 82 c0 104 12 149 51 188 39 38 77 59
                        154 82 77 23 293 20 370 -5z m645 -367 l0 -120 -180 0 -180 0 0 120 0 120 180
                        0 180 0 0 -120z"/>
                        <path d="M775 1501 c-87 -40 -135 -120 -135 -223 1 -142 97 -238 240 -238 144
                        0 240 96 240 240 0 104 -53 187 -142 224 -52 22 -153 20 -203 -3z m183 -91
                        c130 -79 74 -280 -78 -280 -152 0 -208 201 -78 280 43 26 113 26 156 0z"/>
                    </g>
                </svg>
                '
        ],
        [
            'id' => 3,
            'label' => 'Membership limited',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="25px" height="25px" fill="red" >
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                        </svg>'
        ],
        [
            'id' => 4,
            'label' => 'Membership End',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="25px" height="25px" fill="#FB8E28">
	<path d="M11 20a2 2 0 0 1-2-2v-6H2a2 2 0 0 1-2-2V8l2.3-6.12A3.11 3.11 0 0 1 5 0h8a2 2 0 0 1 2 2v8l-3 7v3h-1zm6-10V0h3v10h-3z"/>
</svg>'
        ],
        [
            'id' => 5,
            'label' => 'Pending Approval',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="25px" height="25px" fill="#4682B4">
	<path d="M4 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm6 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm6 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/>
</svg>'
        ],
        [
            'id' => 6,
            'label' => 'Suspended',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="25px" height="25px" fill="#CBB861">
                            <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z"/>
                        </svg>'
        ],
    );




}





