<?php
/** Adminer - Compact database management
* @link http://www.adminer.org/
* @author Jakub Vrana, http://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 3.6.3
*/error_reporting(6135);$gc=!ereg('^(unsafe_raw)?$',ini_get("filter.default"));if($gc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Vf=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Vf)$$X=$Vf;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0Ñ\0\n @\0¥CÑË\"\0`E„Q∏‡ˇá?¿tvM'îJd¡d\\åb0\0ƒ\"ô¿f”à§Ós5õœÁ—AùXPaJì0Ñ•ë8Ñ#RäT©ëz`à#.©«cÌX√˛»Ä?¿-\0°Im?†.´M∂Ä\0»Ø(Ãâ˝¿/(%å\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1ÃáìŸåﬁl7úáB1Ñ4vb0òÕfsëºÍn2BÃ—±Ÿòﬁn:á#(ºb.\rDc)»»a7EÑë§¬l¶√±îËi1Ãésò¥Á-4ôáf”	»Œi7Ü≥ÈÜÑéåF√©ñ®aç'3I– d´¬!S±Êæ:4Áß+MdÂgØã¨«É°Óˆtô∞cëÜ£ı„È†b{èH(∆ì—ît1…)t⁄}F¶p0ôï8Ë\\82õDL>Ç9`'C°º€ó889§» éxQÿ˛\0Óe4ôÕQ òl¡≠P±øVâ≈bÒëóΩT4≤\\ûW/ôÊÈ’\nêÄ` 7\"hƒqπË4ZM6£T÷\r≠r\\ñ∂C{h€7\r”x67Œ©∫J á2.3êÂ9àKûÎ¢H¢,å!mî∆Üo\$„π.[\r&Ó#\$≤<¡àfÕ)èZ£\0=œr®è9√‹jŒ™J†Ë0´c,|Œ=ë√‚˘ΩÍö°Rs_6£Ñ›∑≠˚Ç·…ÌÄZ6£2Bæp\\-á1s2…“>éÉ X:\r‹∫ñ»3ªbö√ºÕ-8SLı¿Ìº…K.¸¥-‹“•\rH@ml·:¢Îµ;Æ˙˛¶ÓJ£0LR–2¥!Ëø´ÂAÍà∆2§	m˝—Ì0eI¡≠-:U\r¸„9‘ıMWLª0˚πGcJv2(ÎÎF9é`¬<áJÑ7+Àö~†çï}DJµΩHWÕSN÷«Ôe◊u]1Ã•(O‘L–™<l˛“R[u&™ÉH⁄3èvÚÄõ‹Uàt6∑√\$¡6‡ﬂ‡X\"ò<£ª}:Oã‰<3x≈O§8Û>†ÃÏÏCŒ⁄Ô1É¢Å’HR‚π’SñdÅ9™‡π%µU1ñSnÊa|.˜‘Å`Í†8£†∂:#Ä ‡CŒ2ã∏*[o·Ü4Xx˙.k\">∫°A™ÕO+,Ûx\\5tò—Ü÷Å`\\≈oûèçà%ßj⁄Ò]∏™n˚»\\È£h‹=éz»√™2\$®´÷F[NYè’Œ”RØ˝[IÙ±’⁄7≤®t“î∂˛7éÏ(·úÔÃWj0ˆÛ§Ê2v}›Ú;Ôk2å–Va–‡ªÒûr=¯ã(À„¢,≥õ\rÏÈj*∫B(RÓ2CñN\\åŒˇ≤9{a\0≈ï”VR4éB∏Ã/zÒnŒ6å£öá≠ÌÅ“(wÉs·sÌÚ∏«¥B[Û¯Mi#:#¯ØU·˛=M-~±ı‰„h)Øı	ÉpåCõ9/,–rÿ=ÉsëÉêò#BvŒäÅM èt=˜@ñhsÕÖÑ`k°Ûåp.=Së\"ŒÓjìÉ&5ƒué—p#Yçúüøá˙Y	‚∂~)∞s4Ÿ√1naV*ÊƒTS·Àqê§6Ü\"[LgÜ¨ë∆B\"|¿í2üéQ§:8∆®’ûÉ\rùVÉ∂4‡aj!º.&ñŒ√Éòo%0F9\"\$Òê≤D»π„â?'®ô2B¥Aﬂga≈kr≠'\$9\rÿ†6π`eœª diÙ˚2p\\õ\$ª“>Ç7Ò\n\\£ñ,°§ƒ9¢Á öIÇÃ+îööLnπ]†HHJú∑ ÂKLehA˘ö™@ˇµÿíÛ@ÜÃ⁄®d ΩÉ˘*ëH10ˆ–êf!‹∏7»1HAù`∞§4ã?ûã∆ZèUº w@(¥R(⁄œ∫T…2°©0Rÿ¯î·D`éÑëb˚ﬂqäqiÈ≈ËhV Íj[!πSìX˚:“\nü0F√L¬¢v…j€¶¡‘9¬ÂJÅfTx7Üz\\œÀõ˝Ü”É+R@“èd›^G¡\0.c¯`≤˛>NèÂ\\ΩF£sˆgñi∑\$ﬂØ%AÈÀ¥TI‡ï@v.ô\0ÃP≈+É cNEı—ßDﬁK∫v«Êe9äÊÊÎ≥Æ*uËVZ`¯⁄=~d¶∂•€De◊}fö”π\0∫≥€5œ¨πüf:jÑ`»ME1»ØE∂®ò CAÅé}∂öç)ê:ná¶U∞F‚YL¨≤Ωf?e+âà.ÎZQ‡ZCxz°‘£õ(`–Àã~Aà5ñ[«yJ1;Ω÷}›NSùL	Ÿ)ê•ñaøãŒ’Sﬁè¿c©ï9Ö¥±2Êmt+ï⁄bﬂcRTôw|•‹7+9˝\\0Éô¬SA≥á Ë·∫g∂ÿü:‘TÒ.'ù-˚bãÙq¶2–9cúP†S˚H÷π¶#ï¸„1©¿´•öØ⁄Í≈gTî‘Qµûi÷º]uÒé(v`ÿ´Î’\r‡Ó>1⁄Àkè~-_ËÎ,ú¨£ÒÒ¿ò·◊0&Cò≥\$¶∆¸<0ÚÍà{•ï∫Üg0ˆ·÷≤ÿ∑ìÔ%´B˙Í“˛v2Ûó.Ωs™b“v‡ªhëdù„Bw˙uﬁ=ãcﬁC÷Eá@>á–");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:õågCIº‹\n:ÃÊsaîPi2\nOgc	»e6LÜÛ‘⁄e7∆s)–ã\rè»HGíIí∆∆3aÑÊs'c„—D i6úNå£—Ëú—2H„Ò8úuF§RÖ#≥îîÍr7á#©îv}Ä@†ê`Qåﬁo5öa‘Iú‹,2O'8îR-q:PÕ∆S∏(àaºä*wÉ(∏Á%ø‡pí<F)‹nx8‰zA\"≥Z-C€e∏V'àßÉÅ¶™s¢‰q’˚;NFì1‰≠≤9ÎGºÕ¶'0ô\r¶õŸ»ø±9n`√—ÄúX1©›ÅG3Ã‡tçee9äÆ:NeÌä˝N±–OSÚz¯cëåzlé`5‚„Å»ﬂ	≥3‚Òyﬂ¸8.ä\r„êŒπP‹˙\rÉ@ç£Æÿ\\1\r„ Û\0Ç@2j8ÿó=.∫¶∞ -r»√°®¨0çäËQ®Íä∫hƒbºåÅÏ`Å¿éª^9ãq⁄E!£ í7)#¿∫™* ¿Q∆»ã\0äÿ“1´»Ê\"ëh >Éÿ˙∞––∆⁄-C \"í‰XÆáS`\\ºè§F÷¨h8‡ä≤‚†¬3ß£`X:OÒö,™á´⁄˙)£8ä“<BN–É;>9¡8“Ûácº<á*¨Ê2éc•9œÈ >¢H¿zôOj™B'B™˙™éä∫≤å5ë,ÚÑPÏb5–45Ä÷3Ïˇ@ÖÅ:∑N+iöjõ’J¢ü⁄ä\\™é	®∆á·@º>ã†∆4Xr(QrèÅ RŸ° dÛÖu=œtÕA8A{åc\\äß)≥Ω|◊ÅC4\n6ÿWÌò7(V4l6µ	ñ9\r°vÀéaﬁ&:CKòç!Ê-°£pŒ:\r\0V¢M†QÜ#ıK@º\0“.ÖŸÄÀVy∑õ¶wE˚\"’„fÉ|jﬁbgŸºF>ƒã	BHnñ›∫Z¢‘B≤B∆\$…F0ïœ√=™ÒkC-9ç„∏˛C¥‰âOîç9^Z3\r«rÖ7‡÷0Í®uŸw˘ïM∏g˘≈∞†’v2∂‘qI∞ºÛ≤ÏˆpÂµ¿∏‰h5c„Py«ÖŸ.Êç[∞˜•hV'-çY¿Ã”T∞Ë”Ö›õí:v–˛ü®O&&6ä®Zó·»Œª	rn˚∂ï•æBc£o⁄ Õ0˙MÉ„£§≤xz]‘åô’çÍ«!ÄvÈdz/sÉ‚€CåÌë¶=ıd9KπeVXŸs:pÃ—à8ñr√A0&iÆ)÷§R\$˙Y_VÈ4¨æÖz˜»;ia∞4∞† lI,&êt5¿¯8øI‰#_©ÄásàF√ª`\niE<¶'—Jy0@∏4á0Ü√a5ò>¬3Ã∫®@ƒï0DÜæœkŒ2ÍuOÇÄB¬Ò[Äº4¿2d•V´<e—`6ê¯C·ƒ	d>'EwAL[ã©iSEıR√[∫ßÅÂ\räﬂê†x<·Ì\\+¢ø xxÉpêû¬\0ËË;ÖA¨ΩpÿˆCÍÆV	ÒY´Rø&ö3∫v®y6 'y)xíjµÍ@‘rIÙÆzhÅBp◊IëífØ∫≈Ü±J˘“Zrù§<…\"\$T∂ë¶^H»Û,3ÊêêPØ≤Ëúê 8fÅ,2áò¶‚âÇp ËNﬂsÊ|Ú∏ûNUÁsCMØ†≤∫t_∞nça–‘,íBŸVbŒp4Û∂§¸€\$w#Áä5%ï8tõÒƒ‡Ùœ¸”ùmØ‡PŸ¯˛Ÿ3˝nØ˝éœ§≤hÛ6Il∫èNrÜQ	î“e‘Eì2⁄Lf'dóÃ*è\":YM #ELz†—h\0%S≠¢DH1–◊!ÍS&≥Ÿ[5	3%√({®»Â^<â…(HÅ¥6X+Œ\"h—W<∏íÛ_êtz∏à9≠Zôuh¨ÖÄîóf^»ã=h”¯:+Bìq≈	P&Ñ¬ä	Épba¿ö\nÆaÏà±V2«W†Ÿöä& ·πZÖ\0ﬁna”›≠dX8Zv>ßùπî–™áb\nCòSJröáªZ‡åW%`[¯/hÄR{\$í-ÖÇ™Åe ≤BGL¯zNBp(∫@¶›∞˜sÉ(fdÚÈG‡bS5“57i?ÜPZt\nzú>å˘`]x0=øJcrRBy»Yì]c_#¢Sìï-‰/Â\0√\n®l– aﬁÉ1áÅ2á@WÖ∞∆\n‰AîÏ#a,1Ö¬¯dí‚0» %aÜèd5ÄÃ|πE∂∆Â¿ŸkBØÔ¯OK+Ã™PÛç~6«0•êﬂeØÍ5á!2@§√∏(vdb¿Â∑q.É@ad≈˛õ¥ÑãRCπàíe¨”)X:#cYæZ.|ÿcﬂZ4>ÿÁ/&‹P≠Vë∏¶2ï∆wûH≤B5ÓÉWÁBpù<p—Œ'<Eüª ö*ê:zFD¥TgHÍR™\\∂√*›+}ºÖ∞¬C–]Ål.@Vº8\$¿≈NjÄõ:Ic·ˆíœΩñ4kï°^Ö˙<ß)˛∆”∏¬\0StÄ∆£•!olju∏∑óÆ÷ÀZkmqÆµÂv⁄€qú%“∫QM€€Æ-jÔYù3˝áﬂ&0°î6ÔIv{{◊ ¸ë‡‹C}∏∂Jz0€Vt”ÿnb-∏l©˘§ÕìÃZWKÄ≠2»x◊⁄m—’F>\nCsÖéàøàÍn„Cﬂ!ÂÀM∆0O…√rìΩ/á∆h2äBÜdUs≤˛é ≠‰ñÉ◊\"XKÅ>}√@¥√Ù@–ß	˜4ﬁ•7D\0†»C∑Ÿ'oGÏÕ\"o˙¯v„îoMvñ„Hy%\$Fù§çv\nUÇ¡zBãê¥ªói)›∫måQ!\"¥º\n;Ï¶‡\0§¢M*öC\rOj∞¢vÓFDºO‡	π/(íÑ¸ÀªÛyÉ;À¿˚ﬂg/Å}‰t9˙l£B[¨∏i‹0›2⁄˘A≤nòõÇ˙Ñ˜∑pKã&‹›∏Í˝\n!o{=º◊âa*êgE\\ûâ1Ω…˚¯ìM¡~√Ÿ{ÑÌ7Ê∏._¡ÊîíàAüﬁkˇØˆ“b>'yÜë¥—∆ˇ ç*nÔnV˚ÇJ¬H¬\"P\$F™*Èxeœ®! ›±\0ßÜp «ôDF#1'ﬁbÉ†ƒ8eLDi\n∏â,ìN+*®˘f˘ØÿôOhk0&;”/‰Hh¢Æ,!Ë|¸¨	#õ/í/â»ˆCBà<pEÄNÅ(π\0(ÊTá∞èW\n«Ü¡\"J(†æj¨ƒ\rc@√@®\r\">·%LÃ∞\$âbEpﬁ¿˙èøäæ ‰ä\r\"dœûÀ¢∫™êIÔ“˛Öj(ÄhÖO‹†fà¸èv¸∆yêÿ˚Fø\0¯˝/¿¸C§«ãx¢oÃ˝I∫˛±ò/dÍ∑F™¢F\0>®>∂Îr∑qTü,U–<ﬂcËìã\nV§ÉL•f#	æãµ\0P®èMö\$¿P&ãqS•Y\n≤LÂhÏÏÔâIb,RHH)+Lµu±p≈ R±Æ∂eœEkãJ¥Ò0k¶xbC*ı∞LÇÄA%löÃpà˜®0a`Ô©òSN\$6CÕi\nb Ièm'∞V–V+M\r«¡¨÷Ãd†á%å®ÑÖ‡⁄H(h€ÕR‹ø≠pÍCœ“>FÔEƒÑåF•§\n-†ö √é‡®*˙º#à®Úp6`œ'rzÄ–•\nn¿€'í|t‡˙•¢™\r¨í†ÇJ£f˙É)‡t∆PSã.Æ=&Úõ'2è*î√ò¶…˜)“’'Úã'R„*L~`Ä¬\rRxÆ·ÑX\rÄƒ’`÷.\0%å*†‚+•J'à\\I\rê¨@Ë10®ÂS 	ì%2ÄÀ2∆f tÜ\0\\#\\6 ∞	‡ƒ\rDo3¯SP ìU5ì\\ cƒ	®:>¿Ê¬‚E3k\nÄ†<‡uEX¡s1DÏös@\$@ﬁCOnÒ\n)Ä†	‡¶\nÜ\0‡ã;\0Rí˙Æ∆/\r9ìî0¬K2≥î	 )-,Ë~\$#.†®èÜ‘Y≠˙[®:æÆR\0Z?¿ÓßÜ≤˙\$Ã) …,Sì\nìÃì—\nì‘8#d<@∞¥T§ä¨G,%õ8´3”öSîT©h;§,T£ŒÃ∆í∞Í7f]D\"EDÄÛ!√à/ÛaT\\i‰ô9≥ÃI§PëÔ\"t]#B/2xÜ`Ë¨J…/èv\"R/H¿ÒIÖÃ¥sÙ\\∆C\0∑ç<LEÉƒ<@WJ„Nµ≤\nè˙3‡RÌdh˝n< Ûƒ‘\n¬≠ P˝sPÄ∂˝qÃ¥W‰Ñπá\\ã®ôà‹ÄÓ\"†‹©n\ro∞Ö0xe¿Cœf∫§¡\n)ˆˇÑÍOÄ√ÜfâJÛII\nÂWR¨qã	èI…©*fÒP¢±Qû!†‰T’]PÍjò\$i-àEä¶M£à≥ki/˙™o˚R†∏Â(WFôŒ∫®ä‹˜#∫/.öåØ˙)à›SF\0ŸÌ’-‚˝o°EH1˛¯√.U©≥JŸ	Æö±~¡•‡dÚ˛õµÊ¯‡Ÿ≤RQ!|±#d±kµîEà\$Î´î^Œ‡ûƒ\"JÎ≠‹Z’>ÙUB˘¡S≈FN≥ﬁ/\"Cbı˛8¥ÃN¶A)¨å∆à\\≈Úó'fQ.£cF2Ï\\eﬁÄXO3‡‡êLç”ÀP,ÚKÕP’MXıé‡Œê‘êñ|∏5∂K%∑g¿zœf3Uá<Û¢äÄÍ\0`#õcŒ∆r«0(J†≤¿\rfÛÆπj’ûHhh5ÛŒÓík÷‹á#Ê:Ø / §Ù¢¶LÔ)“Æ\nÄﬁ¥√àé¨e‚B˜(82‡≥@oÃ∆Vßo‡»≥æÉo¨ÓW\00067È∂Bp‚arBIsÃ4§ëpÇB<WTóBIùtÕa7is»‹‰i{cˆ≥çÌcÓﬂÈH!Íoi€∞o‹(ÄeUR'J\0PæsÙæÎû3‡LRtV†™\n@íá‡€LÉ.åµ|\"#>(∂ïEt´E≥?<¥LW\"Ë⁄≤ﬂb6ß}tLb0˜Gdrﬁ‘v0\"\$ß“õ~V#V°ó«ÄñÍF„_FUfYOÅÕn¬\ru¯c&]m∏#ÅO¨·˜ind\$HVÔZ Àor=så«s‡Àu„ƒŒ÷¸JG6ÄZ•∂\"‰`V∑¯L\$ò37ûË(h	Äﬁ8.*†P XÉêIà∏~ÎÎzk0!£8≥W{X#§øûøl#W¯¢±√‡8*\$¡k\"L#*≤Ø+\$≠+ÉƒøA†øœ\\4´É*yÓûm\nmD…ã\0Â`@òºtƒTî…˙Î∂®c¯˙≤wÓ∑GhrToE…'FVëç´D7v©i”ëg¿yà–ÒånVQr∞¯ƒ6.àp6´\0ˆ¬8®ø’e¿÷†Vãy\$áKΩxjΩ†Wh∆xØ`Ûw¬ÇËÉ/!≤\\Q¯˚Ú\nÏπSı\\Ÿï9˘ô9‰ˇ?OMä≤˝+Ë B0PDp*ß#&†:@‘∑\0– „Ó=„‚O+h>‚8àˆ?£˛@2ÜD@ŒÚPÇ`^4£|8&87·˘ÀŸ—9iÑ8â)É; ‚<KyBß¢mP##∆∆eH]J4-	.lç¿^bo£⁄zã¬∫ú‚Œ√¿O•bg§Ø@;Nñré»„»(”÷<ÌÿC†Cóá¢w D#±°ÿEZc∆:£’!`w®Éÿ=⁄W°#?¶\0‘p«©C÷ ÿ∂G6ñe’“Ü:V\"Za°‚Ú˙&5ı\r‡E‚j\r¿÷@@*¢2KHπ„d*†‰CA¶˙K`Ê ÿ˙˛´±ØèwØ€\0›öUú¿]Ø£\r∞5±[í˚í˘Ù∑(‘:C¯) Ì)¬Á∞f\r‡⁄‰ Îü)ÓRRd5„ˆ\$\0^è÷4§\0œ∞bä≠'≥ô‰Ü+~ÄŒ4˚D@§B\0Áü†Wµ¢?w⁄Éπ˙u±€m∞`ø∫{!ª&K{¨\rõªe/∂{°€∞„È∂˚Æõªº√Ù‚∏\$0ÃΩ¬Ã\r«f‡[∂I∫æÍ“`é\n†áæ≠x¡zæ⁄V	ö}˙∆∑\r∫Õ¿ª‹	≠÷´¶é‡–Éú(ÄæR∏VV£ùf°©∫±9¿›F⁄F,√E:∫o…´däÇÕ{‚/(‚ÂQã©‘aF\\d‘Bñö∂Ù¿˚¶nîkaß◊Ñ\$_ƒ8w¶\n`Ï–Àå¥`Á ·«Í”‹ê∑lÁ¸è0*h\rFÛ≈Vˆ•u&q.Å…Z@\nÄ‰\n%«»Î‡Áé∞@Ú≥:@'ú›éµ¨ˇ±WÕ<◊,W4Ë¶vLRÓ,É‡„àÉf¿@…—#dq√íYp†ÏdY¶“†∂®£-,m∂Äm„í‰kﬂÀ'V9(ÁÇxÔ;\0À∆Üg“cêÛâÓ)Çé ¢îæ<ìòïÆ;≤ˆqsóRË6ïÙ¯Ø“˘\0¯µ¯¿àÛ1C`z(ÛÃ4á4\rÿH\$ÖçDÿ⁄Vﬁ ÿé∫<àÄ!§s⁄C´‹w≤ê=~]÷M„\npDT≠,\0Y€DÏ3‡∏<≈)\nÿgﬁ\$+hÀ\nÿAÇdê*}∏ù®¥]Æ!›Ìñ˝ÍçÏ<:ì‡]Œà=“G^+‹í%Ã1ÙìÍ±,æ\rÇgÑWG·o1¶àü€§¨òGoñ2;\0Oﬂ#A\"˛:Úu⁄â5ﬂyÄny'!G‡bÁÁ”N:c›:d[)À\r?8gÎﬁ¢â)X‘∆ıâxÒÔ\"Ç^i\"æ±[–bÃÏﬂ#¨⁄xeå-yimäK#Qx„lMö°äoM\\®£äSw^í⁄>‘Ä÷#\0à:DÄm∑Àê±\"˛∫ÿïríúØ–Ò÷ΩmJíß·Ì≠ólm\$HJFõÇ· ‡íæ¥¢Ä‡√PL^ÑÌé@‘æÏu_á-Há.ÔÛ¬2Ω›&\$dÎ≤ûÖŒ®»S√d0¶X? ∏ß\n4dcˆBD|Ãˆ•œÏø7ˆøê\r\0∂ ∫3Ûû†OO#§Kø bÄ∑˘ˇ£˙`NKø¨›Ω‹¡cÛM¥⁄I(~EÆPiæä‡ò	 ñ†¯\n@ã;@∞\nXT”ü\0¯ÑÁ°-˛/ÛBx	Ñ∫4ä•d¯#	¿Jqû>úLÿy\0~D¿%–â=ôÊ´VCÛﬂp„ùæ}˝†.`X_~¸<?\r\$dÍ#…\0¬`3∏∏·+0@@Ë7!‰ `|| Ê\0æﬂÕÒ+–B ‡◊ê?#<ÅÎÊﬁ∫˝ï^?0EœŒ\0πﬂæ¸øç<W |é\$∞¸ê\$?0\rÅ®HBñ–?à¨∏Z¥@LEá5Ü…ˇoƒ)rMà≠6?†Ö·£[Ëyß≠ıÍ«íZ¥B\$Â0≤ë˝8_z§ Ò°\$AÚ¡tÄÀ‚!5ãÖ†+‡)%·ÜîîVPñídÇd\0\0÷¡Ä\0Ü\n∏∞pTP.ÃÀ?Å∞∆úîñM‰@ELJóÑ∫∆\"ây¬∫Z∏Q\0¬/äLPU‡g\0W∞>?j¿bÄ@†Å9Åº\n¶˛MÉÙª¸“¡–Sÿ¡ `\"áPﬂØÜq∂mˆV\r‰∏\0ƒe;°‰ÏÔbqüEåj3>öùﬂŒÚÄZ“åË@%≥ÄµÁg]s®‹ö!Û7q\$éQ¯g¿∂“Î.A9‰¸*ö}z'ëåî@ÃG_\"ƒy¬ c—;yßÄZ–ã¬·Å \n@¿àö¿°¯q,{”ª†∂ÁëÂõq\$qF<∞@Îƒµf@2 L4¢y0à9¸ãBáÇ—`I1fáúÅ.]:âõƒÄ˝\"A†¨+ÉÖ`BÍ◊Ô?HÎß8ãD_†!%†–p!®àªE«VU≠l∞]«ëp\ní/â/}Á«B:6:≈(e#©¯⁄Jßëÿ\$¬G8Ü`Fc80B·ÒP∫Ö‡a|\"»ø4ÑP^ÙfêH(Œ∆|·ê^çŒ‚Ö8®∏…ÃX\$<lRÄ≈ê™≈3ç∞cê„`BD\0=äÑIZ@gWôµ÷Â»á…Då®ec,\0°·oK˘ñä¥§zëÊ:†w)L˜@ê•≤;eGz…V\"À1uö⁄Ä\\s+ÄR¯ùé:BY@ÑNª7Ô`^ØﬁãÄ·\\%8Åº?É8[:h£˛æ@∑¸:F{tÉ'ä7Kmr{\\°£hh#BQrï69_\$qs≥d“í¯‰*eÖœH|ÏØî\r˚ÂÇßß*»qÙÒçë(çD(«∂ﬁF„1ä®∏\0Pm¿Ä‰œ”x\0iê8&¬ê‰íàh\$S`Üåó≤%Zô≤\$T”ÉI+i‚m∑ë8‰Rt√GI`Ÿœq\\ÅQ\$9#clFÓföëì“Jº©»Ï•·¿2p√à∑ÄÌ¿¸àÉì<OO:â-»ô‘§Ïy¨ü_π& 0≤=Ãè¿a\$!å∞^G;¡¨‡\0î`>ˆ@mÿ\n¸d’|ãºÄrâ6 %\r3w≠¨årñ3¿ø): Ür§É™§π(1|>r<√Píi∑OH‚Öä¥Î˙¨?#£l»—à∂ a’f\"à!“a£LÑ^ûoSR“¸°+í+Åír¬Gx…•ãêáÙ+Ïñ‚=Çø rÒÄú9°TÖúÃ<∑çóÎ∞¯ñDÖï`ÿô\\À1”1ü_∂ÿáÑ∫:qòY\0•⁄ff…Ë¨±9i‘ÄH-ôˆ#2/7¬8Õ\0 2≤é¿;,\$É„Ëiîd°Ösq™ÄÚR“ÌÊo\\g0ÇlD˘°(Îí¸ó(Ês2tï>êˇëƒq» yä#—JP@m!µa¡H!Õ ëyFáÌ#è!mËìq‹ëfò„Õh—Œd+πKè(ã-ù˙…x∏XX¿1*ÌŒPI9Y0Ê~ìùôÑSpõíà∑*3˛ä±\"·	\0G†åY∫#n”CBÄO@›8c\rN%S≥ÅaåWÂ*°ºN-	AE G‘LΩ¯î∆G^,E•[*Ú ÄìkÉ|ÁP®†8*q\r.L3|–Ñ51ùDZV\n˘àÀ„ÊJŸ\\º÷lÆ]ù»	‡¿Ó4˝Dd≥.¶îl€»‹ây«Å∂p‚võ˙\0Á\nÄß∫Ã)'9)Ú/…£:9‚Ål !πpëgîuê†÷Q´ÖîÀ¿È)„Ñ∆`)\"gJy3ß™™yÎ¡†*¥û‚ªO<I¥O ”®qàgŒ=C≠H‚R≥ü»¿'¥'2≥9£&Jà \0åºà*%´ìdÒäNÒ8È»N\"~„3öí¯ãhä”Ñ¢Ω†g;Ú≈9Sá√ÂÉÕ5:i\\√≈8P6Ä∂/ó≥ˆ¢P˛X|æ1‘‚íÅ ¶hãB1S@`ö@\n\0b»ƒ%y±Ä»¶Y\"%cÄàLì\nm¿OE®4à±‡¬m^íGôéDJe`.ôhMf_\nAÇà4k£h\\ÇäBﬁ\r®&2√=Yn}3»}PT\r\0Bi•®–p˘ÉQ«ä%DèG»îô—~CyZ9\0Ëî◊†7\$EîTZ0ô^káL\$¶,˝¢ıË∑J%¢≠i-zmÄWIä0´2 4t!ÏÀ®ÓÍ<— èî¥,ù¶aG\"B%˝Änò#¢>à—”\r—√\\y@H§?C`√Â@^(,ï‰B”ëgÕU”sW(‡È&∫ÁSSZEÉhP8\ngèL4œ¶ÅCËûƒ˛Á∂ñ'Ø^_£&@D\0 I‘ ƒüä,K0qVbÄ0amßM3∆i˛Úw ”:L¶ÑÓ…¯PQÙJË{'8¶,tQÙ¿«,W‚±e¯k¬W,b’6‘\"µ0üô¿)	…⁄„¸SØ:èt*hÙ~‡	Í8éı˚ø\\ZTÍ	Ÿ{gSÄ7Tò!§,	îí√FÎ ®úÂ÷Œ@[¢ÿBT«:õTé®\0-t∏ô'ôSq<’¥Ì†|ˇi„UÄ◊ÀúÙµ!3—ñ\$,°@êsUÄ^‰2heæõ\"ëá™´òÓ'.	íã.≠UUr%óÜp\"YÀÄÃí¡}<F&P∂Å∫9ÛààÔÄ˜¡KàáAq¸ÇÍÕWë@ˆe5V´i†≈jhR{ªÖÒÊåß€Õ&Cóπy ¶ú¶˘ÃMK''’à‹‘”•C+_Ä,–öË◊µØ5¸ìƒç#a÷b±™Ä(èD…FV†9∞ÒÖ©+Qc7¬ŒïëZ]ej cö·Vj≠uúê≈g‰5Z'÷IVC’iΩ5ﬂ+\"95V®£ï¨JS®ç}*Pù◊Ω%Ds@Êk6Wú◊uª}dìàs%\n‡©∆ò6µx™ù\"\nq§ üu©8˘GM≠#4∑=ÊQIL64ú©9_j¸ò^5˚^1©™Ó˙ÏôΩ“Ãu(-rG\\≥◊«Tê¸ÉÈ +Ü]ÁÅ¶HÁÑeY…˛,∫Ì*zöµ‰{qŸ√≈\"„ÆåÍ.ÇKëÅG!aáØ Ñ∞åL÷\"+!—úÀW≠ËÅXÄ÷“æîî¿\nçp–-åÍ#2®\n+fÄŸD\r@o x°ìP≤W⁄9ˆ-=Ωf¨Õc*ÈDJ∫ïÑØµã Ωb˚; R∫∆â!°%lÆÊ;,ãjaÔøuóÖõfåFR	#™5åÌ)[È(«mMI=`èL˙„ÉZ\"H∂êΩ[´ΩWN>®Áë]i§\\nÀTàﬁl–+¥Umlπaì[ÿl›6±Â•F>ˆêÏZÆ¥∂>∞QC©Õ&íñõi)∂Zd[ÔÌq_´]∞…›∆ˆ±	ó-NZæ◊&\"8£Ió«å∑»≤å†0F`alÃ@Í°)Á8QÙá·ó\"|∂Á˘˙mÊÄ¶`áÔ@AòPlY¯·≥iq´â°–—åVa£hs/∏ZÇBõÇàa”8N{0ÀënêÜ›ÅáòlÄÏÌ¯©A+˘Q»>`tùt!§⁄	·≈•p‡:(NH‡/C H€óÆ8®Fπ†N∫v‚©€˙’íb⁄hx\0™<© Ø¢≤)€NÌdÜùŸ‘¥	'C{¬˚∂n5YKg`\\£,yTÒËò0πÌ«;Â‘ÜÄWP¶=@·OªΩ‹@`àCÜ Ä4N<Ç⁄R¿£|wo/¬–ç¶ÔW|≈ﬂ®´Ω›qì°ó1MƒåYqIÿáÅOÿ• ¿Ë∫Ñc¢9º!€0/<—8``Hn{hßÜÏ√87Ÿé“π„}ÑJÚ◊òYPùf◊z=˚‡-º∞\n]Cy⁄5ﬁX=Êàg√¿ ˚’›º«h;O\rªFµìå¢Û7≥æ0[iySÆRˆ∑∑=ªΩ/,€›?J˜À@Ωyååé[·^XW’æ@§æ”kéfË÷«≈ÙHîªﬁ_f˝ì˛(™P‘ê32#ò·Ùy°ò◊Èû«;Œ›∆Ù\0.¿eË‚kzjOã4π‡lòº*∆≤ú˚Ã\$<27…∏8I]”s€õ¡ròø.o!Ç®ùÑÄ◊≠;≠˚ØI–§†!9˜Î_òçŸUKä—Ê˘V	øõOh÷.Ä∂ï†∑€ÎR`à2—æ∞Wu|ì¥√†+ΩûÔéº'¥j/õæÿÄàÀÅÅA‡œ\nÿM∫HIp\0ÙÁ¬Åˆ‹BC¬’˙pßÑ¿–aÆ¸∆4HyT”ü≤Ø[†^ˆÎ°HW0H–∏C	¯kπ…ÜXìê~Â7Ç-<EÓÖWUâíéÆä†=Rxûúªú. óπÂ‰±3p;òÄ∂Êa‡^ÓG	íîafˆÑBõâkºñú‘ÒÜH\nœ÷ßÄÆ˚aˆ+Ìﬁw•<∞G˜Z·˛\0_åEÆ|êX∫Ì±x£ÏF	/“∆1qçåãD K⁄^‚˜ó¿∫÷ø;≈‡‰πSC0P´¯]ÄÍJ}Ô_&ì±Q¢Ã™òx¿º◊È∆2\rËZÉƒœ@‚∂fØò\0ƒæQIÅÃÄ3p@");}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo"GIF87a\0\0°\0\0ÓÓÓ\0\0\0ôôô\0\0\0,\0\0\0\0\0\0\0!Ñè©ÀÌMÒÃ*)æo˙Ø) qï°eàµÓ#ƒÚLÀ\0;";break;case"cross.gif":echo"GIF87a\0\0°\0\0ÓÓÓ\0\0\0ôôô\0\0\0,\0\0\0\0\0\0\0#Ñè©ÀÌ#\na÷Fo~y√.Å_waî·1Á±JÓG¬L◊6]\0\0;";break;case"up.gif":echo"GIF87a\0\0°\0\0ÓÓÓ\0\0\0ôôô\0\0\0,\0\0\0\0\0\0\0 Ñè©ÀÌMQN\nÔ}Ùûa8äyöa≈∂Æ\0«Ú\0;";break;case"down.gif":echo"GIF87a\0\0°\0\0ÓÓÓ\0\0\0ôôô\0\0\0,\0\0\0\0\0\0\0 Ñè©ÀÌMÒÃ*)æ[W˛\\¢«L&Ÿú∆∂ï\0«Ú\0;";break;case"arrow.gif":echo"GIF89a\0\n\0Ä\0\0ÄÄÄˇˇˇ!˘\0\0\0,\0\0\0\0\0\n\0\0Çiñ±ãûî™”≤ﬁª\0\0;";break;}}exit;}function
connection(){global$f;return$f;}function
adminer(){global$b;return$b;}function
idf_unescape($Ac){$Uc=substr($Ac,-1);return
str_replace($Uc.$Uc,$Uc,substr($Ac,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
remove_slashes($ue,$gc=false){if(get_magic_quotes_gpc()){while(list($x,$X)=each($ue)){foreach($X
as$Oc=>$W){unset($ue[$x][$Oc]);if(is_array($W)){$ue[$x][stripslashes($Oc)]=$W;$ue[]=&$ue[$x][stripslashes($Oc)];}else$ue[$x][stripslashes($Oc)]=($gc?$W:stripslashes($W));}}}}function
bracket_escape($Ac,$ya=false){static$If=array(':'=>':1',']'=>':2','['=>':3');return
strtr($Ac,($ya?array_flip($If):$If));}function
h($P){return
htmlspecialchars(str_replace("\0","",$P),ENT_QUOTES);}function
nbsp($P){return(trim($P)!=""?h($P):"&nbsp;");}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($D,$Y,$Ka,$Sc="",$Id="",$Nc=false){static$s=0;$s++;$J="<input type='checkbox' name='$D' value='".h($Y)."'".($Ka?" checked":"").($Id?' onclick="'.h($Id).'"':'').($Nc?" class='jsonly'":"")." id='checkbox-$s'>";return($Sc!=""?"<label for='checkbox-$s'>$J".h($Sc)."</label>":$J);}function
optionlist($Ld,$Ve=null,$bg=false){$J="";foreach($Ld
as$Oc=>$W){$Md=array($Oc=>$W);if(is_array($W)){$J.='<optgroup label="'.h($Oc).'">';$Md=$W;}foreach($Md
as$x=>$X)$J.='<option'.($bg||is_string($x)?' value="'.h($x).'"':'').(($bg||is_string($x)?(string)$x:$X)===$Ve?' selected':'').'>'.h($X);if(is_array($W))$J.='</optgroup>';}return$J;}function
html_select($D,$Ld,$Y="",$Hd=true){if($Hd)return"<select name='".h($D)."'".(is_string($Hd)?' onchange="'.h($Hd).'"':"").">".optionlist($Ld,$Y)."</select>";$J="";foreach($Ld
as$x=>$X)$J.="<label><input type='radio' name='".h($D)."' value='".h($x)."'".($x==$Y?" checked":"").">".h($X)."</label>";return$J;}function
confirm($eb=""){return" onclick=\"return confirm('".'Are you sure?'.($eb?" (' + $eb + ')":"")."');\"";}function
print_fieldset($s,$Zc,$hg=false,$Id=""){echo"<fieldset><legend><a href='#fieldset-$s' onclick=\"".h($Id)."return !toggle('fieldset-$s');\">$Zc</a></legend><div id='fieldset-$s'".($hg?"":" class='hidden'").">\n";}function
bold($Ea){return($Ea?" class='active'":"");}function
odd($J=' class="odd"'){static$r=0;if(!$J)$r=-1;return($r++%2?$J:'');}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
json_row($x,$X=null){static$hc=true;if($hc)echo"{";if($x!=""){echo($hc?"":",")."\n\t\"".addcslashes($x,"\r\n\"\\").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\").'"':'undefined');$hc=false;}else{echo"\n}\n";$hc=true;}}function
ini_bool($Ec){$X=ini_get($Ec);return(eregi('^(on|true|yes)$',$X)||(int)$X);}function
sid(){static$J;if($J===null)$J=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$J;}function
q($P){global$f;return$f->quote($P);}function
get_vals($H,$Ta=0){global$f;$J=array();$I=$f->query($H);if(is_object($I)){while($K=$I->fetch_row())$J[]=$K[$Ta];}return$J;}function
get_key_vals($H,$g=null){global$f;if(!is_object($g))$g=$f;$J=array();$I=$g->query($H);if(is_object($I)){while($K=$I->fetch_row())$J[$K[0]]=$K[1];}return$J;}function
get_rows($H,$g=null,$j="<p class='error'>"){global$f;$ab=(is_object($g)?$g:$f);$J=array();$I=$ab->query($H);if(is_object($I)){while($K=$I->fetch_assoc())$J[]=$K;}elseif(!$I&&!is_object($g)&&$j&&defined("PAGE_HEADER"))echo$j.error()."\n";return$J;}function
unique_array($K,$u){foreach($u
as$t){if(ereg("PRIMARY|UNIQUE",$t["type"])){$J=array();foreach($t["columns"]as$x){if(!isset($K[$x]))continue
2;$J[$x]=$K[$x];}return$J;}}$J=array();foreach($K
as$x=>$X){if(!preg_match('~^(COUNT\\((\\*|(DISTINCT )?`(?:[^`]|``)+`)\\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\\(`(?:[^`]|``)+`\\))$~',$x))$J[$x]=$X;}return$J;}function
where($Z,$l=array()){global$w;$J=array();foreach((array)$Z["where"]as$x=>$X)$J[]=idf_escape(bracket_escape($x,1)).(($w=="sql"&&ereg('\\.',$X))||$w=="mssql"?" LIKE ".exact_value(addcslashes($X,"%_\\")):" = ".unconvert_field($l[$x],exact_value($X)));foreach((array)$Z["null"]as$x)$J[]=idf_escape($x)." IS NULL";return
implode(" AND ",$J);}function
where_check($X,$l=array()){parse_str($X,$Ja);remove_slashes(array(&$Ja));return
where($Ja,$l);}function
where_link($r,$Ta,$Y,$Jd="="){return"&where%5B$r%5D%5Bcol%5D=".urlencode($Ta)."&where%5B$r%5D%5Bop%5D=".urlencode(($Y!==null?$Jd:"IS NULL"))."&where%5B$r%5D%5Bval%5D=".urlencode($Y);}function
cookie($D,$Y){global$ba;$Zd=array($D,(ereg("\n",$Y)?"":$Y),time()+2592000,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Zd[]=true;return
call_user_func_array('setcookie',$Zd);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session(){if(!ini_bool("session.use_cookies"))session_write_close();}function&get_session($x){return$_SESSION[$x][DRIVER][SERVER][$_GET["username"]];}function
set_session($x,$X){$_SESSION[$x][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($yb,$N,$V,$i=null){global$zb;preg_match('~([^?]*)\\??(.*)~',remove_from_uri(implode("|",array_keys($zb))."|username|".($i!==null?"db|":"").session_name()),$A);return"$A[1]?".(sid()?SID."&":"").($yb!="server"||$N!=""?urlencode($yb)."=".urlencode($N)."&":"")."username=".urlencode($V).($i!=""?"&db=".urlencode($i):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($_,$B=null){if($B!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($_!==null?$_:$_SERVER["REQUEST_URI"]))][]=$B;}if($_!==null){if($_=="")$_=".";header("Location: $_");exit;}}function
query_redirect($H,$_,$B,$ze=true,$Wb=true,$cc=false){global$f,$j,$b;if($Wb)$cc=!$f->query($H);$df="";if($H)$df=$b->messageQuery("$H;");if($cc){$j=error().$df;return
false;}if($ze)redirect($_,$B.$df);return
true;}function
queries($H=null){global$f;static$xe=array();if($H===null)return
implode(";\n",$xe);$xe[]=(ereg(';$',$H)?"DELIMITER ;;\n$H;\nDELIMITER ":$H);return$f->query($H);}function
apply_queries($H,$uf,$Rb='table'){foreach($uf
as$R){if(!queries("$H ".$Rb($R)))return
false;}return
true;}function
queries_redirect($_,$B,$ze){return
query_redirect(queries(),$_,$B,$ze,false,!$ze);}function
remove_from_uri($Yd=""){return
substr(preg_replace("~(?<=[?&])($Yd".(SID?"":"|".session_name()).")=[^&]*&~",'',"$_SERVER[REQUEST_URI]&"),0,-1);}function
pagination($E,$jb){return" ".($E==$jb?$E+1:'<a href="'.h(remove_from_uri("page").($E?"&page=$E":"")).'">'.($E+1)."</a>");}function
get_file($x,$pb=false){$ec=$_FILES[$x];if(!$ec||$ec["error"])return$ec["error"];$J=file_get_contents($pb&&ereg('\\.gz$',$ec["name"])?"compress.zlib://$ec[tmp_name]":($pb&&ereg('\\.bz2$',$ec["name"])?"compress.bzip2://$ec[tmp_name]":$ec["tmp_name"]));if($pb){$ef=substr($J,0,3);if(function_exists("iconv")&&ereg("^\xFE\xFF|^\xFF\xFE",$ef,$Fe))$J=iconv("utf-16","utf-8",$J);elseif($ef=="\xEF\xBB\xBF")$J=substr($J,3);}return$J;}function
upload_error($j){$ld=($j==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($j?'Unable to upload a file.'.($ld?" ".sprintf('Maximum allowed file size is %sB.',$ld):""):'File does not exist.');}function
repeat_pattern($F,$ad){return
str_repeat("$F{0,65535}",$ad/65535)."$F{0,".($ad%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\\0-\\x8\\xB\\xC\\xE-\\x1F]~',$X));}function
shorten_utf8($P,$ad=80,$kf=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{FFFF}]",$ad).")($)?)u",$P,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$ad).")($)?)",$P,$A);return
h($A[1]).$kf.(isset($A[2])?"":"<i>...</i>");}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($ue,$Bc=array()){while(list($x,$X)=each($ue)){if(is_array($X)){foreach($X
as$Oc=>$W)$ue[$x."[$Oc]"]=$W;}elseif(!in_array($x,$Bc))echo'<input type="hidden" name="'.h($x).'" value="'.h($X).'">';}}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
column_foreign_keys($R){global$b;$J=array();foreach($b->foreignKeys($R)as$m){foreach($m["source"]as$X)$J[$X][]=$m;}return$J;}function
enum_input($U,$va,$k,$Y,$Kb=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$k["length"],$gd);$J=($Kb!==null?"<label><input type='$U'$va value='$Kb'".((is_array($Y)?in_array($Kb,$Y):$Y===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($gd[1]as$r=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ka=(is_int($Y)?$Y==$r+1:(is_array($Y)?in_array($r+1,$Y):$Y===$X));$J.=" <label><input type='$U'$va value='".($r+1)."'".($Ka?' checked':'').'>'.h($b->editVal($X,$k)).'</label>';}return$J;}function
input($k,$Y,$o){global$Qf,$b,$w;$D=h(bracket_escape($k["field"]));echo"<td class='function'>";$He=($w=="mssql"&&$k["auto_increment"]);if($He&&!$_POST["save"])$o=null;$p=(isset($_GET["select"])||$He?array("orig"=>'original'):array())+$b->editFunctions($k);$va=" name='fields[$D]'";if($k["type"]=="enum")echo
nbsp($p[""])."<td>".$b->editInput($_GET["edit"],$k,$va,$Y);else{$hc=0;foreach($p
as$x=>$X){if($x===""||!$X)break;$hc++;}$Hd=($hc?" onchange=\"var f = this.form['function[".h(js_escape(bracket_escape($k["field"])))."]']; if ($hc > f.selectedIndex) f.selectedIndex = $hc;\"":"");$va.=$Hd;echo(count($p)>1?html_select("function[$D]",$p,$o===null||in_array($o,$p)||isset($p[$o])?$o:"","functionChange(this);"):nbsp(reset($p))).'<td>';$Gc=$b->editInput($_GET["edit"],$k,$va,$Y);if($Gc!="")echo$Gc;elseif($k["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$k["length"],$gd);foreach($gd[1]as$r=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ka=(is_int($Y)?($Y>>$r)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$D][$r]' value='".(1<<$r)."'".($Ka?' checked':'')."$Hd>".h($b->editVal($X,$k)).'</label>';}}elseif(ereg('blob|bytea|raw|file',$k["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$D'$Hd>";elseif(($zf=ereg('text|lob',$k["type"]))||ereg("\n",$Y)){if($zf&&$w!="sqlite")$va.=" cols='50' rows='12'";else{$L=min(12,substr_count($Y,"\n")+1);$va.=" cols='30' rows='$L'".($L==1?" style='height: 1.2em;'":"");}echo"<textarea$va>".h($Y).'</textarea>';}else{$md=(!ereg('int',$k["type"])&&preg_match('~^(\\d+)(,(\\d+))?$~',$k["length"],$A)?((ereg("binary",$k["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$k["unsigned"]?1:0)):($Qf[$k["type"]]?$Qf[$k["type"]]+($k["unsigned"]?0:1):0));echo"<input".(ereg('int',$k["type"])?" type='number'":"")." value='".h($Y)."'".($md?" maxlength='$md'":"").(ereg('char|binary',$k["type"])&&$md>20?" size='40'":"")."$va>";}}}function
process_input($k){global$b;$Ac=bracket_escape($k["field"]);$o=$_POST["function"][$Ac];$Y=$_POST["fields"][$Ac];if($k["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($k["auto_increment"]&&$Y=="")return
null;if($o=="orig")return($k["on_update"]=="CURRENT_TIMESTAMP"?idf_escape($k["field"]):false);if($o=="NULL")return"NULL";if($k["type"]=="set")return
array_sum((array)$Y);if(ereg('blob|bytea|raw|file',$k["type"])&&ini_bool("file_uploads")){$ec=get_file("fields-$Ac");if(!is_string($ec))return
false;return
q($ec);}return$b->processInput($k,$Y,$o);}function
search_tables(){global$b,$f;$_GET["where"][0]["op"]="LIKE %%";$_GET["where"][0]["val"]=$_POST["query"];$mc=false;foreach(table_status()as$R=>$S){$D=$b->tableName($S);if(isset($S["Engine"])&&$D!=""&&(!$_POST["tables"]||in_array($R,$_POST["tables"]))){$I=$f->query("SELECT".limit("1 FROM ".table($R)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($R),array())),1));if(!$I||$I->fetch_row()){if(!$mc){echo"<ul>\n";$mc=true;}echo"<li>".($I?"<a href='".h(ME."select=".urlencode($R)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$D</a>\n":"$D: <span class='error'>".error()."</span>\n");}}}echo($mc?"</ul>":"<p class='message'>".'No tables.')."\n";}function
dump_headers($_c,$td=false){global$b;$J=$b->dumpHeaders($_c,$td);$Wd=$_POST["output"];if($Wd!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($_c).".$J".($Wd!="file"&&!ereg('[^0-9a-z]',$Wd)?".$Wd":""));session_write_close();return$J;}function
dump_csv($K){foreach($K
as$x=>$X){if(preg_match("~[\"\n,;\t]~",$X)||$X==="")$K[$x]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$K)."\r\n";}function
apply_sql_function($o,$Ta){return($o?($o=="unixepoch"?"DATETIME($Ta, '$o')":($o=="count distinct"?"COUNT(DISTINCT ":strtoupper("$o("))."$Ta)"):$Ta);}function
password_file(){$vb=ini_get("upload_tmp_dir");if(!$vb){if(function_exists('sys_get_temp_dir'))$vb=sys_get_temp_dir();else{$fc=@tempnam("","");if(!$fc)return
false;$vb=dirname($fc);unlink($fc);}}$fc="$vb/adminer.key";$J=@file_get_contents($fc);if($J)return$J;$oc=@fopen($fc,"w");if($oc){$J=md5(uniqid(mt_rand(),true));fwrite($oc,$J);fclose($oc);}return$J;}function
is_mail($Hb){$ua='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$xb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$F="$ua+(\\.$ua+)*@($xb?\\.)+$xb";return
preg_match("(^$F(,\\s*$F)*\$)i",$Hb);}function
is_url($P){$xb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return(preg_match("~^(https?)://($xb?\\.)+$xb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P,$A)?strtolower($A[1]):"");}function
is_shortable($k){return
ereg('char|text|lob|geometry|point|linestring|polygon',$k["type"]);}function
slow_query($H){global$b,$T;$i=$b->database();if(support("kill")&&is_object($g=connect())&&($i==""||$g->select_db($i))){$Qc=$g->result("SELECT CONNECTION_ID()");echo'<script type="text/javascript">
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'token=',$T,'&kill=',$Qc,'\');
}, ',1000*$b->queryTimeout(),');
</script>
';}else$g=null;ob_flush();flush();$J=@get_key_vals($H,$g);if($g){echo"<script type='text/javascript'>clearTimeout(timeout);</script>\n";ob_flush();flush();}return
array_keys($J);}function
lzw_decompress($Ba){$ub=256;$Ca=8;$Oa=array();$Ie=0;$Je=0;for($r=0;$r<strlen($Ba);$r++){$Ie=($Ie<<8)+ord($Ba[$r]);$Je+=8;if($Je>=$Ca){$Je-=$Ca;$Oa[]=$Ie>>$Je;$Ie&=(1<<$Je)-1;$ub++;if($ub>>$Ca)$Ca++;}}$tb=range("\0","\xFF");$J="";foreach($Oa
as$r=>$Na){$Gb=$tb[$Na];if(!isset($Gb))$Gb=$lg.$lg[0];$J.=$Gb;if($r)$tb[]=$lg.$Gb[0];$lg=$Gb;}return$J;}global$b,$f,$zb,$Eb,$Ob,$j,$p,$uc,$ba,$Fc,$w,$ca,$Tc,$Gd,$he,$if,$T,$Kf,$Qf,$Xf,$ga;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";$ba=$_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_name("adminer_sid");$Zd=array(0,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Zd[]=true;call_user_func_array('session_set_cookie_params',$Zd);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$gc);if(function_exists("set_magic_quotes_runtime"))set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",20);function
get_lang(){return'en';}function
lang($Jf,$zd){$je=($zd==1?0:1);$Jf=str_replace("%d","%s",$Jf[$je]);$zd=number_format($zd,0,".",',');return
sprintf($Jf,$zd);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$errno,$error;function
__construct(){global$b;$je=array_search("",$b->operators);if($je!==false)unset($b->operators[$je]);}function
dsn($Bb,$V,$ge,$Vb='auth_error'){set_exception_handler($Vb);parent::__construct($Bb,$V,$ge);restore_exception_handler();$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=$this->getAttribute(4);}function
query($H,$Rf=false){$I=parent::query($H);$this->error="";if(!$I){list(,$this->errno,$this->error)=$this->errorInfo();return
false;}$this->store_result($I);return$I;}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result($I=null){if(!$I){$I=$this->_result;if(!$I)return
false;}if($I->columnCount()){$I->num_rows=$I->rowCount();return$I;}$this->affected_rows=$I->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($H,$k=0){$I=$this->query($H);if(!$I)return
false;$K=$I->fetch();return$K[$k];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$K=(object)$this->getColumnMeta($this->_offset++);$K->orgtable=$K->table;$K->orgname=$K->name;$K->charsetnr=(in_array("blob",(array)$K->flags)?63:0);return$K;}}}$zb=array();$zb=array("server"=>"MySQL")+$zb;if(!defined("DRIVER")){$me=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
Min_DB(){parent::init();}function
connect($N,$V,$ge){mysqli_report(MYSQLI_REPORT_OFF);list($yc,$ie)=explode(":",$N,2);$J=@$this->real_connect(($N!=""?$yc:ini_get("mysqli.default_host")),($N.$V!=""?$V:ini_get("mysqli.default_user")),($N.$V.$ge!=""?$ge:ini_get("mysqli.default_pw")),null,(is_numeric($ie)?$ie:ini_get("mysqli.default_port")),(!is_numeric($ie)?$ie:null));if($J){if(method_exists($this,'set_charset'))$this->set_charset("utf8");else$this->query("SET NAMES utf8");}return$J;}function
result($H,$k=0){$I=$this->query($H);if(!$I)return
false;$K=$I->fetch_array();return$K[$k];}function
quote($P){return"'".$this->escape_string($P)."'";}}}elseif(extension_loaded("mysql")&&!(ini_get("sql.safe_mode")&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($N,$V,$ge){$this->_link=@mysql_connect(($N!=""?$N:ini_get("mysql.default_host")),("$N$V"!=""?$V:ini_get("mysql.default_user")),("$N$V$ge"!=""?$ge:ini_get("mysql.default_password")),true,131072);if($this->_link){$this->server_info=mysql_get_server_info($this->_link);if(function_exists('mysql_set_charset'))mysql_set_charset("utf8",$this->_link);else$this->query("SET NAMES utf8");}else$this->error=mysql_error();return(bool)$this->_link;}function
quote($P){return"'".mysql_real_escape_string($P,$this->_link)."'";}function
select_db($mb){return
mysql_select_db($mb,$this->_link);}function
query($H,$Rf=false){$I=@($Rf?mysql_unbuffered_query($H,$this->_link):mysql_query($H,$this->_link));$this->error="";if(!$I){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($I===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($I);}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($H,$k=0){$I=$this->query($H);if(!$I||!$I->num_rows)return
false;return
mysql_result($I->_result,0,$k);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
Min_Result($I){$this->_result=$I;$this->num_rows=mysql_num_rows($I);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$J=mysql_fetch_field($this->_result,$this->_offset++);$J->orgtable=$J->table;$J->orgname=$J->name;$J->charsetnr=($J->blob?63:0);return$J;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($N,$V,$ge){$this->dsn("mysql:host=".str_replace(":",";unix_socket=",preg_replace('~:(\\d)~',';port=\\1',$N)),$V,$ge);$this->query("SET NAMES utf8");return
true;}function
select_db($mb){return$this->query("USE ".idf_escape($mb));}function
query($H,$Rf=false){$this->setAttribute(1000,!$Rf);return
parent::query($H,$Rf);}}}function
idf_escape($Ac){return"`".str_replace("`","``",$Ac)."`";}function
table($Ac){return
idf_escape($Ac);}function
connect(){global$b;$f=new
Min_DB;$ib=$b->credentials();if($f->connect($ib[0],$ib[1],$ib[2])){$f->query("SET sql_quote_show_create = 1, autocommit = 1");return$f;}$J=$f->error;if(function_exists('iconv')&&!is_utf8($J)&&strlen($Re=iconv("windows-1250","utf-8",$J))>strlen($J))$J=$Re;return$J;}function
get_databases($ic){global$f;$J=get_session("dbs");if($J===null){$H=($f->server_info>=5?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA":"SHOW DATABASES");$J=($ic?slow_query($H):get_vals($H));restart_session();set_session("dbs",$J);stop_session();}return$J;}function
limit($H,$Z,$y,$Ad=0,$Xe=" "){return" $H$Z".($y!==null?$Xe."LIMIT $y".($Ad?" OFFSET $Ad":""):"");}function
limit1($H,$Z){return
limit($H,$Z,1);}function
db_collation($i,$d){global$f;$J=null;$fb=$f->result("SHOW CREATE DATABASE ".idf_escape($i),1);if(preg_match('~ COLLATE ([^ ]+)~',$fb,$A))$J=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$fb,$A))$J=$d[$A[1]][-1];return$J;}function
engines(){$J=array();foreach(get_rows("SHOW ENGINES")as$K){if(ereg("YES|DEFAULT",$K["Support"]))$J[]=$K["Engine"];}return$J;}function
logged_user(){global$f;return$f->result("SELECT USER()");}function
tables_list(){global$f;return
get_key_vals("SHOW".($f->server_info>=5?" FULL":"")." TABLES");}function
count_tables($h){$J=array();foreach($h
as$i)$J[$i]=count(get_vals("SHOW TABLES IN ".idf_escape($i)));return$J;}function
table_status($D=""){$J=array();foreach(get_rows("SHOW TABLE STATUS".($D!=""?" LIKE ".q(addcslashes($D,"%_")):""))as$K){if($K["Engine"]=="InnoDB")$K["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["Comment"]);if(!isset($K["Rows"]))$K["Comment"]="";if($D!="")return$K;$J[$K["Name"]]=$K;}return$J;}function
is_view($S){return!isset($S["Rows"]);}function
fk_support($S){return
eregi("InnoDB|IBMDB2I",$S["Engine"]);}function
fields($R){$J=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($R))as$K){preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',$K["Type"],$A);$J[$K["Field"]]=array("field"=>$K["Field"],"full_type"=>$K["Type"],"type"=>$A[1],"length"=>$A[2],"unsigned"=>ltrim($A[3].$A[4]),"default"=>($K["Default"]!=""||ereg("char|set",$A[1])?$K["Default"]:null),"null"=>($K["Null"]=="YES"),"auto_increment"=>($K["Extra"]=="auto_increment"),"on_update"=>(eregi('^on update (.+)',$K["Extra"],$A)?$A[1]:""),"collation"=>$K["Collation"],"privileges"=>array_flip(explode(",",$K["Privileges"])),"comment"=>$K["Comment"],"primary"=>($K["Key"]=="PRI"),);}return$J;}function
indexes($R,$g=null){$J=array();foreach(get_rows("SHOW INDEX FROM ".table($R),$g)as$K){$J[$K["Key_name"]]["type"]=($K["Key_name"]=="PRIMARY"?"PRIMARY":($K["Index_type"]=="FULLTEXT"?"FULLTEXT":($K["Non_unique"]?"INDEX":"UNIQUE")));$J[$K["Key_name"]]["columns"][]=$K["Column_name"];$J[$K["Key_name"]]["lengths"][]=$K["Sub_part"];}return$J;}function
foreign_keys($R){global$f,$Gd;static$F='`(?:[^`]|``)+`';$J=array();$gb=$f->result("SHOW CREATE TABLE ".table($R),1);if($gb){preg_match_all("~CONSTRAINT ($F) FOREIGN KEY \\(((?:$F,? ?)+)\\) REFERENCES ($F)(?:\\.($F))? \\(((?:$F,? ?)+)\\)(?: ON DELETE ($Gd))?(?: ON UPDATE ($Gd))?~",$gb,$gd,PREG_SET_ORDER);foreach($gd
as$A){preg_match_all("~$F~",$A[2],$bf);preg_match_all("~$F~",$A[5],$xf);$J[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('idf_unescape',$bf[0]),"target"=>array_map('idf_unescape',$xf[0]),"on_delete"=>($A[6]?$A[6]:"RESTRICT"),"on_update"=>($A[7]?$A[7]:"RESTRICT"),);}}return$J;}function
view($D){global$f;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\\s+AS\\s+~isU','',$f->result("SHOW CREATE VIEW ".table($D),1)));}function
collations(){$J=array();foreach(get_rows("SHOW COLLATION")as$K){if($K["Default"])$J[$K["Charset"]][-1]=$K["Collation"];else$J[$K["Charset"]][]=$K["Collation"];}ksort($J);foreach($J
as$x=>$X)asort($J[$x]);return$J;}function
information_schema($i){global$f;return($f->server_info>=5&&$i=="information_schema")||($f->server_info>=5.5&&$i=="performance_schema");}function
error(){global$f;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$f->error));}function
error_line(){global$f;if(ereg(' at line ([0-9]+)$',$f->error,$Fe))return$Fe[1]-1;}function
exact_value($X){return
q($X)." COLLATE utf8_bin";}function
create_database($i,$Ra){set_session("dbs",null);return
queries("CREATE DATABASE ".idf_escape($i).($Ra?" COLLATE ".q($Ra):""));}function
drop_databases($h){set_session("dbs",null);return
apply_queries("DROP DATABASE",$h,'idf_escape');}function
rename_database($D,$Ra){if(create_database($D,$Ra)){$Ge=array();foreach(tables_list()as$R=>$U)$Ge[]=table($R)." TO ".idf_escape($D).".".table($R);if(!$Ge||queries("RENAME TABLE ".implode(", ",$Ge))){queries("DROP DATABASE ".idf_escape(DB));return
true;}}return
false;}function
auto_increment(){$xa=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$t){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$t["columns"],true)){$xa="";break;}if($t["type"]=="PRIMARY")$xa=" UNIQUE";}}return" AUTO_INCREMENT$xa";}function
alter_table($R,$D,$l,$jc,$Wa,$Mb,$Ra,$wa,$de){$sa=array();foreach($l
as$k)$sa[]=($k[1]?($R!=""?($k[0]!=""?"CHANGE ".idf_escape($k[0]):"ADD"):" ")." ".implode($k[1]).($R!=""?$k[2]:""):"DROP ".idf_escape($k[0]));$sa=array_merge($sa,$jc);$ff="COMMENT=".q($Wa).($Mb?" ENGINE=".q($Mb):"").($Ra?" COLLATE ".q($Ra):"").($wa!=""?" AUTO_INCREMENT=$wa":"").$de;if($R=="")return
queries("CREATE TABLE ".table($D)." (\n".implode(",\n",$sa)."\n) $ff");if($R!=$D)$sa[]="RENAME TO ".table($D);$sa[]=$ff;return
queries("ALTER TABLE ".table($R)."\n".implode(",\n",$sa));}function
alter_indexes($R,$sa){foreach($sa
as$x=>$X)$sa[$x]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"").$X[2]);return
queries("ALTER TABLE ".table($R).implode(",",$sa));}function
truncate_tables($uf){return
apply_queries("TRUNCATE TABLE",$uf);}function
drop_views($gg){return
queries("DROP VIEW ".implode(", ",array_map('table',$gg)));}function
drop_tables($uf){return
queries("DROP TABLE ".implode(", ",array_map('table',$uf)));}function
move_tables($uf,$gg,$xf){$Ge=array();foreach(array_merge($uf,$gg)as$R)$Ge[]=table($R)." TO ".idf_escape($xf).".".table($R);return
queries("RENAME TABLE ".implode(", ",$Ge));}function
copy_tables($uf,$gg,$xf){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($uf
as$R){$D=($xf==DB?table("copy_$R"):idf_escape($xf).".".table($R));if(!queries("DROP TABLE IF EXISTS $D")||!queries("CREATE TABLE $D LIKE ".table($R))||!queries("INSERT INTO $D SELECT * FROM ".table($R)))return
false;}foreach($gg
as$R){$D=($xf==DB?table("copy_$R"):idf_escape($xf).".".table($R));$fg=view($R);if(!queries("DROP VIEW IF EXISTS $D")||!queries("CREATE VIEW $D AS $fg[select]"))return
false;}return
true;}function
trigger($D){if($D=="")return
array();$L=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($D));return
reset($L);}function
triggers($R){$J=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_")))as$K)$J[$K["Trigger"]]=array($K["Timing"],$K["Event"]);return$J;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Type"=>array("FOR EACH ROW"),);}function
routine($D,$U){global$f,$Ob,$Fc,$Qf;$pa=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$Pf="((".implode("|",array_merge(array_keys($Qf),$pa)).")\\b(?:\\s*\\(((?:[^'\")]*|$Ob)+)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s]+)['\"]?)?";$F="\\s*(".($U=="FUNCTION"?"":$Fc).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Pf";$fb=$f->result("SHOW CREATE $U ".idf_escape($D),2);preg_match("~\\(((?:$F\\s*,?)*)\\)\\s*".($U=="FUNCTION"?"RETURNS\\s+$Pf\\s+":"")."(.*)~is",$fb,$A);$l=array();preg_match_all("~$F\\s*,?~is",$A[1],$gd,PREG_SET_ORDER);foreach($gd
as$Yd){$D=str_replace("``","`",$Yd[2]).$Yd[3];$l[]=array("field"=>$D,"type"=>strtolower($Yd[5]),"length"=>preg_replace_callback("~$Ob~s",'normalize_enum',$Yd[6]),"unsigned"=>strtolower(preg_replace('~\\s+~',' ',trim("$Yd[8] $Yd[7]"))),"null"=>1,"full_type"=>$Yd[4],"inout"=>strtoupper($Yd[1]),"collation"=>strtolower($Yd[9]),);}if($U!="FUNCTION")return
array("fields"=>$l,"definition"=>$A[11]);return
array("fields"=>$l,"returns"=>array("type"=>$A[12],"length"=>$A[13],"unsigned"=>$A[15],"collation"=>$A[16]),"definition"=>$A[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT * FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
begin(){return
queries("BEGIN");}function
insert_into($R,$O){return
queries("INSERT INTO ".table($R)." (".implode(", ",array_keys($O)).")\nVALUES (".implode(", ",$O).")");}function
insert_update($R,$O,$pe){foreach($O
as$x=>$X)$O[$x]="$x = $X";$Yf=implode(", ",$O);return
queries("INSERT INTO ".table($R)." SET $Yf ON DUPLICATE KEY UPDATE $Yf");}function
last_id(){global$f;return$f->result("SELECT LAST_INSERT_ID()");}function
explain($f,$H){return$f->query("EXPLAIN $H");}function
found_rows($S,$Z){return($Z||$S["Engine"]!="InnoDB"?null:$S["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($Te){return
true;}function
create_sql($R,$wa){global$f;$J=$f->result("SHOW CREATE TABLE ".table($R),1);if(!$wa)$J=preg_replace('~ AUTO_INCREMENT=\\d+~','',$J);return$J;}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
use_sql($mb){return"USE ".idf_escape($mb);}function
trigger_sql($R,$Q){$J="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_")),null,"-- ")as$K)$J.="\n".($Q=='CREATE+ALTER'?"DROP TRIGGER IF EXISTS ".idf_escape($K["Trigger"]).";;\n":"")."CREATE TRIGGER ".idf_escape($K["Trigger"])." $K[Timing] $K[Event] ON ".table($K["Table"])." FOR EACH ROW\n$K[Statement];;\n";return$J;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($k){if(ereg("binary",$k["type"]))return"HEX(".idf_escape($k["field"]).")";if(ereg("geometry|point|linestring|polygon",$k["type"]))return"AsWKT(".idf_escape($k["field"]).")";}function
unconvert_field($k,$J){if(ereg("binary",$k["type"]))$J="UNHEX($J)";if(ereg("geometry|point|linestring|polygon",$k["type"]))$J="GeomFromText($J)";return$J;}function
support($dc){global$f;return!ereg("scheme|sequence|type".($f->server_info<5.1?"|event|partitioning".($f->server_info<5?"|view|routine|trigger":""):""),$dc);}$w="sql";$Qf=array();$if=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Geometry'=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$x=>$X){$Qf+=$X;$if[$x]=array_keys($X);}$Xf=array("unsigned","zerofill","unsigned zerofill");$Kd=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","");$p=array("char_length","date","from_unixtime","lower","round","sec_to_time","time_to_sec","upper");$uc=array("avg","count","count distinct","group_concat","max","min","sum");$Eb=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array("(^|[^o])int|float|double|decimal"=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",preg_replace('~^[^?]*/([^?]*).*~','\\1',$_SERVER["REQUEST_URI"]).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ga="3.6.3";class
Adminer{var$operators;function
name(){return"<a href='http://www.adminer.org/' id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_session("pwds"));}function
permanentLogin(){return
password_file();}function
database(){return
DB;}function
databases($ic=true){return
get_databases($ic);}function
queryTimeout(){return
5;}function
headers(){return
true;}function
head(){return
true;}function
loginForm(){global$zb;echo'<table cellspacing="0">
<tr><th>System<td>',html_select("auth[driver]",$zb,DRIVER,"loginDriver(this);"),'<tr><th>Server<td><input name="auth[server]" value="',h(SERVER),'" title="hostname[:port]">
<tr><th>Username<td><input id="username" name="auth[username]" value="',h($_GET["username"]),'">
<tr><th>Password<td><input type="password" name="auth[password]">
<tr><th>Database<td><input name="auth[db]" value="',h($_GET["db"]);?>">
</table>
<script type="text/javascript">
var username = document.getElementById('username');
username.focus();
username.form['auth[driver]'].onchange();
</script>
<?php

echo"<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
login($ed,$ge){return
true;}function
tableName($pf){return
h($pf["Name"]);}function
fieldName($k,$Nd=0){return'<span title="'.h($k["full_type"]).'">'.h($k["field"]).'</span>';}function
selectLinks($pf,$O=""){echo'<p class="tabs">';$dd=array("select"=>'Select data',"table"=>'Show structure');if(is_view($pf))$dd["view"]='Alter view';else$dd["create"]='Alter table';if($O!==null)$dd["edit"]='New item';foreach($dd
as$x=>$X)echo" <a href='".h(ME)."$x=".urlencode($pf["Name"]).($x=="edit"?$O:"")."'".bold(isset($_GET[$x])).">$X</a>";echo"\n";}function
foreignKeys($R){return
foreign_keys($R);}function
backwardKeys($R,$of){return
array();}function
backwardKeysPrint($za,$K){}function
selectQuery($H){global$w;return"<p><a href='".h(remove_from_uri("page"))."&amp;page=last' title='".'Last page'."'>&gt;&gt;</a> <code class='jush-$w'>".h(str_replace("\n"," ",$H))."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a></p>\n";}function
rowDescription($R){return"";}function
rowDescriptions($L,$kc){return$L;}function
selectLink($X,$k){}function
selectVal($X,$z,$k){$J=($X===null?"<i>NULL</i>":(ereg("char|binary",$k["type"])&&!ereg("var",$k["type"])?"<code>$X</code>":$X));if(ereg('blob|bytea|raw|file',$k["type"])&&!is_utf8($X))$J=lang(array('%d byte','%d bytes'),strlen($X));return($z?"<a href='".h($z)."'>$J</a>":$J);}function
editVal($X,$k){return$X;}function
selectColumnsPrint($M,$e){global$p,$uc;print_fieldset("select",'Select',$M);$r=0;$qc=array('Functions'=>$p,'Aggregation'=>$uc);foreach($M
as$x=>$X){$X=$_GET["columns"][$x];echo"<div>".html_select("columns[$r][fun]",array(-1=>"")+$qc,$X["fun"]),"(<select name='columns[$r][col]' onchange='selectFieldChange(this.form);'><option>".optionlist($e,$X["col"],true)."</select>)</div>\n";$r++;}echo"<div>".html_select("columns[$r][fun]",array(-1=>"")+$qc,"","this.nextSibling.nextSibling.onchange();"),"(<select name='columns[$r][col]' onchange='selectAddRow(this);'><option>".optionlist($e,null,true)."</select>)</div>\n","</div></fieldset>\n";}function
selectSearchPrint($Z,$e,$u){print_fieldset("search",'Search',$Z);foreach($u
as$r=>$t){if($t["type"]=="FULLTEXT"){echo"(<i>".implode("</i>, <i>",array_map('h',$t["columns"]))."</i>) AGAINST"," <input type='search' name='fulltext[$r]' value='".h($_GET["fulltext"][$r])."' onchange='selectFieldChange(this.form);'>",checkbox("boolean[$r]",1,isset($_GET["boolean"][$r]),"BOOL"),"<br>\n";}}$_GET["where"]=(array)$_GET["where"];reset($_GET["where"]);$Ia="this.nextSibling.onchange();";for($r=0;$r<=count($_GET["where"]);$r++){list(,$X)=each($_GET["where"]);if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators))){echo"<div><select name='where[$r][col]' onchange='$Ia'><option value=''>(".'anywhere'.")".optionlist($e,$X["col"],true)."</select>",html_select("where[$r][op]",$this->operators,$X["op"],$Ia),"<input type='search' name='where[$r][val]' value='".h($X["val"])."' onchange='".($X?"selectFieldChange(this.form)":"selectAddRow(this)").";'></div>\n";}}echo"</div></fieldset>\n";}function
selectOrderPrint($Nd,$e,$u){print_fieldset("sort",'Sort',$Nd);$r=0;foreach((array)$_GET["order"]as$x=>$X){if(isset($e[$X])){echo"<div><select name='order[$r]' onchange='selectFieldChange(this.form);'><option>".optionlist($e,$X,true)."</select>",checkbox("desc[$r]",1,isset($_GET["desc"][$x]),'descending')."</div>\n";$r++;}}echo"<div><select name='order[$r]' onchange='selectAddRow(this);'><option>".optionlist($e,null,true)."</select>","<label><input type='checkbox' name='desc[$r]' value='1'>".'descending'."</label></div>\n";echo"</div></fieldset>\n";}function
selectLimitPrint($y){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input type='number' name='limit' class='size' value='".h($y)."' onchange='selectFieldChange(this.form);'>","</div></fieldset>\n";}function
selectLengthPrint($_f){if($_f!==null){echo"<fieldset><legend>".'Text length'."</legend><div>","<input type='number' name='text_length' class='size' value='".h($_f)."'>","</div></fieldset>\n";}}function
selectActionPrint($u){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script type='text/javascript'>\n","var indexColumns = ";$e=array();foreach($u
as$t){if($t["type"]!="FULLTEXT")$e[reset($t["columns"])]=1;}$e[""]=1;foreach($e
as$x=>$X)json_row($x);echo";\n","selectFieldChange(document.getElementById('form'));\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint($Ib,$e){}function
selectColumnsProcess($e,$u){global$p,$uc;$M=array();$sc=array();foreach((array)$_GET["columns"]as$x=>$X){if($X["fun"]=="count"||(isset($e[$X["col"]])&&(!$X["fun"]||in_array($X["fun"],$p)||in_array($X["fun"],$uc)))){$M[$x]=apply_sql_function($X["fun"],(isset($e[$X["col"]])?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$uc))$sc[]=$M[$x];}}return
array($M,$sc);}function
selectSearchProcess($l,$u){global$w;$J=array();foreach($u
as$r=>$t){if($t["type"]=="FULLTEXT"&&$_GET["fulltext"][$r]!="")$J[]="MATCH (".implode(", ",array_map('idf_escape',$t["columns"])).") AGAINST (".q($_GET["fulltext"][$r]).(isset($_GET["boolean"][$r])?" IN BOOLEAN MODE":"").")";}foreach((array)$_GET["where"]as$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$Za=" $X[op]";if(ereg('IN$',$X["op"])){$Cc=process_length($X["val"]);$Za.=" (".($Cc!=""?$Cc:"NULL").")";}elseif(!$X["op"])$Za.=$X["val"];elseif($X["op"]=="LIKE %%")$Za=" LIKE ".$this->processInput($l[$X["col"]],"%$X[val]%");elseif(!ereg('NULL$',$X["op"]))$Za.=" ".$this->processInput($l[$X["col"]],$X["val"]);if($X["col"]!="")$J[]=idf_escape($X["col"]).$Za;else{$Sa=array();foreach($l
as$D=>$k){$Lc=ereg('char|text|enum|set',$k["type"]);if((is_numeric($X["val"])||!ereg('int|float|double|decimal|bit',$k["type"]))&&(!ereg("[\x80-\xFF]",$X["val"])||$Lc)){$D=idf_escape($D);$Sa[]=($w=="sql"&&$Lc&&!ereg('^utf8',$k["collation"])?"CONVERT($D USING utf8)":$D);}}$J[]=($Sa?"(".implode("$Za OR ",$Sa)."$Za)":"0");}}}return$J;}function
selectOrderProcess($l,$u){$J=array();foreach((array)$_GET["order"]as$x=>$X){if(isset($l[$X])||preg_match('~^((COUNT\\(DISTINCT |[A-Z0-9_]+\\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\\)|COUNT\\(\\*\\))$~',$X))$J[]=(isset($l[$X])?idf_escape($X):$X).(isset($_GET["desc"][$x])?" DESC":"");}return$J;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"30");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$kc){return
false;}function
selectQueryBuild($M,$Z,$sc,$Nd,$y,$E){return"";}function
messageQuery($H){global$w;static$eb=0;restart_session();$s="sql-".($eb++);$wc=&get_session("queries");if(strlen($H)>1e6)$H=ereg_replace('[\x80-\xFF]+$','',substr($H,0,1e6))."\n...";$wc[$_GET["db"]][]=array($H,time());return" <span class='time'>".@date("H:i:s")."</span> <a href='#$s' onclick=\"return !toggle('$s');\">".'SQL command'."</a><div id='$s' class='hidden'><pre><code class='jush-$w'>".shorten_utf8($H,1000).'</code></pre><p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($wc[$_GET["db"]])-1)).'">'.'Edit'.'</a></div>';}function
editFunctions($k){global$Eb;$J=($k["null"]?"NULL/":"");foreach($Eb
as$x=>$p){if(!$x||(!isset($_GET["call"])&&(isset($_GET["select"])||where($_GET)))){foreach($p
as$F=>$X){if(!$F||ereg($F,$k["type"]))$J.="/$X";}if($x&&!ereg('set|blob|bytea|raw|file',$k["type"]))$J.="/=";}}return
explode("/",$J);}function
editInput($R,$k,$va,$Y){if($k["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$va value='-1' checked><i>".'original'."</i></label> ":"").($k["null"]?"<label><input type='radio'$va value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$va,$k,$Y,0);return"";}function
processInput($k,$Y,$o=""){if($o=="=")return$Y;$D=$k["field"];$J=($k["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$Y)?$Y:q($Y));if(ereg('^(now|getdate|uuid)$',$o))$J="$o()";elseif(ereg('^current_(date|timestamp)$',$o))$J=$o;elseif(ereg('^([+-]|\\|\\|)$',$o))$J=idf_escape($D)." $o $J";elseif(ereg('^[+-] interval$',$o))$J=idf_escape($D)." $o ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+$~i",$Y)?$Y:$J);elseif(ereg('^(addtime|subtime|concat)$',$o))$J="$o(".idf_escape($D).", $J)";elseif(ereg('^(md5|sha1|password|encrypt)$',$o))$J="$o($J)";return
unconvert_field($k,$J);}function
dumpOutput(){$J=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$J['gz']='gzip';if(function_exists('bzcompress'))$J['bz2']='bzip2';return$J;}function
dumpFormat(){return
array('sql'=>'SQL','csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpTable($R,$Q,$Mc=false){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($Q)dump_csv(array_keys(fields($R)));}elseif($Q){$fb=create_sql($R,$_POST["auto_increment"]);if($fb){if($Q=="DROP+CREATE")echo"DROP ".($Mc?"VIEW":"TABLE")." IF EXISTS ".table($R).";\n";if($Mc)$fb=remove_definer($fb);echo($Q!="CREATE+ALTER"?$fb:($Mc?substr_replace($fb," OR REPLACE",6,0):substr_replace($fb," IF NOT EXISTS",12,0))).";\n\n";}if($Q=="CREATE+ALTER"&&!$Mc){$H="SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($R)." ORDER BY ORDINAL_POSITION";echo"DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT '";$l=array();$oa="";foreach(get_rows($H)as$K){$qb=$K["COLUMN_DEFAULT"];$K["default"]=($qb!==null?q($qb):"NULL");$K["after"]=q($oa);$K["alter"]=escape_string(idf_escape($K["COLUMN_NAME"])." $K[COLUMN_TYPE]".($K["COLLATION_NAME"]?" COLLATE $K[COLLATION_NAME]":"").($qb!==null?" DEFAULT ".($qb=="CURRENT_TIMESTAMP"?$qb:$K["default"]):"").($K["IS_NULLABLE"]=="YES"?"":" NOT NULL").($K["EXTRA"]?" $K[EXTRA]":"").($K["COLUMN_COMMENT"]?" COMMENT ".q($K["COLUMN_COMMENT"]):"").($oa?" AFTER ".idf_escape($oa):" FIRST"));echo", ADD $K[alter]";$l[]=$K;$oa=$K["COLUMN_NAME"];}echo"';
	DECLARE columns CURSOR FOR $H;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name";foreach($l
as$K)echo"
				WHEN ".q($K["COLUMN_NAME"])." THEN
					SET add_columns = REPLACE(add_columns, ', ADD $K[alter]', IF(
						_column_default <=> $K[default] AND _is_nullable = '$K[IS_NULLABLE]' AND _collation_name <=> ".(isset($K["COLLATION_NAME"])?"'$K[COLLATION_NAME]'":"NULL")." AND _column_type = ".q($K["COLUMN_TYPE"])." AND _extra = '$K[EXTRA]' AND _column_comment = ".q($K["COLUMN_COMMENT"])." AND after = $K[after]
					, '', ', MODIFY $K[alter]'));";echo"
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE ".table($R)."', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;

";}}}function
dumpData($R,$Q,$H){global$f,$w;$id=($w=="sqlite"?0:1048576);if($Q){if($_POST["format"]=="sql"&&$Q=="TRUNCATE+INSERT")echo
truncate_sql($R).";\n";if($_POST["format"]=="sql")$l=fields($R);$I=$f->query($H,1);if($I){$Hc="";$Ga="";$Pc=array();$kf="";while($K=$I->fetch_row()){if(!$Pc){$dg=array();foreach($K
as$X){$k=$I->fetch_field();$Pc[]=$k->name;$x=idf_escape($k->name);$dg[]="$x = VALUES($x)";}$kf=($Q=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$dg):"").";\n";}if($_POST["format"]!="sql"){if($Q=="table"){dump_csv($Pc);$Q="INSERT";}dump_csv($K);}else{if(!$Hc)$Hc="INSERT INTO ".table($R)." (".implode(", ",array_map('idf_escape',$Pc)).") VALUES";foreach($K
as$x=>$X)$K[$x]=($X!==null?(ereg('int|float|double|decimal|bit',$l[$Pc[$x]]["type"])?$X:q($X)):"NULL");$Re=($id?"\n":" ")."(".implode(",\t",$K).")";if(!$Ga)$Ga=$Hc.$Re;elseif(strlen($Ga)+4+strlen($Re)+strlen($kf)<$id)$Ga.=",$Re";else{echo$Ga.$kf;$Ga=$Hc.$Re;}}}if($Ga)echo$Ga.$kf;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",$f->error)."\n";}}function
dumpFilename($_c){return
friendly_url($_c!=""?$_c:(SERVER!=""?SERVER:"localhost"));}function
dumpHeaders($_c,$td=false){$Wd=$_POST["output"];$ac=($_POST["format"]=="sql"?"sql":($td?"tar":"csv"));header("Content-Type: ".($Wd=="bz2"?"application/x-bzip":($Wd=="gz"?"application/x-gzip":($ac=="tar"?"application/x-tar":($ac=="sql"||$Wd!="file"?"text/plain":"text/csv")."; charset=utf-8"))));if($Wd=="bz2")ob_start('bzcompress',1e6);if($Wd=="gz")ob_start('gzencode',1e6);return$ac;}function
homepage(){echo'<p>'.($_GET["ns"]==""?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($sd){global$ga,$T,$w,$zb;echo'<h1>
',$this->name(),' <span class="version">',$ga,'</span>
<a href="http://www.adminer.org/#download" id="version">',(version_compare($ga,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($sd=="auth"){$hc=true;foreach((array)$_SESSION["pwds"]as$yb=>$Ze){foreach($Ze
as$N=>$cg){foreach($cg
as$V=>$ge){if($ge!==null){if($hc){echo"<p id='logins' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>\n";$hc=false;}$ob=$_SESSION["db"][$yb][$N][$V];foreach(($ob?array_keys($ob):array(""))as$i)echo"<a href='".h(auth_url($yb,$N,$V,$i))."'>($zb[$yb]) ".h($V.($N!=""?"@$N":"").($i!=""?" - $i":""))."</a><br>\n";}}}}}else{echo'<form action="" method="post">
<p class="logout">
';if(DB==""||!$sd){echo"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])).">".'SQL command'."</a>\n";if(support("dump"))echo"<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Dump'."</a>\n";}echo'<input type="submit" name="logout" value="Logout" id="logout">
<input type="hidden" name="token" value="',$T,'">
</p>
</form>
';$this->databasesPrint($sd);if($_GET["ns"]!==""&&!$sd&&DB!=""){echo'<p><a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create new table'."</a>\n";$uf=tables_list();if(!$uf)echo"<p class='message'>".'No tables.'."\n";else{$this->tablesPrint($uf);$dd=array();foreach($uf
as$R=>$U)$dd[]=preg_quote($R,'/');echo"<script type='text/javascript'>\n","var jushLinks = { $w: [ '".js_escape(ME)."table=\$&', /\\b(".implode("|",$dd).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.$w;\n";echo"</script>\n";}}}}function
databasesPrint($sd){global$f;$h=$this->databases();echo'<form action="">
<p id="dbs">
';hidden_fields_get();echo($h?html_select("db",array(""=>"(".'database'.")")+$h,DB,"this.form.submit();"):'<input name="db" value="'.h(DB).'">'),'<input type="submit" value="Use"',($h?" class='hidden'":""),'>
';if($sd!="db"&&DB!=""&&$f->select_db(DB)){}echo(isset($_GET["sql"])?'<input type="hidden" name="sql" value="">':(isset($_GET["schema"])?'<input type="hidden" name="schema" value="">':(isset($_GET["dump"])?'<input type="hidden" name="dump" value="">':""))),"</p></form>\n";}function
tablesPrint($uf){echo"<p id='tables' onmouseover='menuOver(this, event);' onmouseout='menuOut(this);'>\n";foreach($uf
as$R=>$U){echo'<a href="'.h(ME).'select='.urlencode($R).'"'.bold($_GET["select"]==$R).">".'select'."</a> ",'<a href="'.h(ME).'table='.urlencode($R).'"'.bold($_GET["table"]==$R)." title='".'Show structure'."'>".$this->tableName(array("Name"=>$R))."</a><br>\n";}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);if($b->operators===null)$b->operators=$Kd;function
page_header($Cf,$j="",$Fa=array(),$Df=""){global$ca,$b,$f,$zb;header("Content-Type: text/html; charset=utf-8");if($b->headers()){header("X-Frame-Options: deny");header("X-XSS-Protection: 0");}$Ef=$Cf.($Df!=""?": ".h($Df):"");$Ff=strip_tags($Ef.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<title>',$Ff,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME))."?file=default.css&amp;version=3.6.3",'">
<script type="text/javascript" src="',h(preg_replace("~\\?.*~","",ME))."?file=functions.js&amp;version=3.6.3",'"></script>
';if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME))."?file=favicon.ico&amp;version=3.6.3",'" id="favicon">
';if(file_exists("adminer.css")){echo'<link rel="stylesheet" type="text/css" href="adminer.css">
';}}echo'
<body class="ltr nojs" onkeydown="bodyKeydown(event);" onclick="bodyClick(event);" onload="bodyLoad(\'',(is_object($f)?substr($f->server_info,0,3):""),'\');',(isset($_COOKIE["adminer_version"])?"":" verifyVersion();"),'">
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, \' js\');
</script>

<div id="content">
';if($Fa!==null){$z=substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($z?$z:".").'">'.$zb[DRIVER].'</a> &raquo; ';$z=substr(preg_replace('~(db|ns)=[^&]*&~','',ME),0,-1);$N=(SERVER!=""?h(SERVER):'Server');if($Fa===false)echo"$N\n";else{echo"<a href='".($z?h($z):".")."' accesskey='1' title='Alt+Shift+1'>$N</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Fa)))echo'<a href="'.h($z."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Fa)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Fa
as$x=>$X){$sb=(is_array($X)?$X[1]:$X);if($sb!="")echo'<a href="'.h(ME."$x=").urlencode(is_array($X)?$X[0]:$X).'">'.h($sb).'</a> &raquo; ';}}echo"$Cf\n";}}echo"<h2>$Ef</h2>\n";restart_session();$Zf=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$qd=$_SESSION["messages"][$Zf];if($qd){echo"<div class='message'>".implode("</div>\n<div class='message'>",$qd)."</div>\n";unset($_SESSION["messages"][$Zf]);}$h=&get_session("dbs");if(DB!=""&&$h&&!in_array(DB,$h,true))$h=null;stop_session();if($j)echo"<div class='error'>$j</div>\n";define("PAGE_HEADER",1);}function
page_footer($sd=""){global$b;echo'</div>

<div id="menu">
';$b->navigation($sd);echo'</div>
';}function
int32($C){while($C>=2147483648)$C-=4294967296;while($C<=-2147483649)$C+=4294967296;return(int)$C;}function
long2str($W,$ig){$Re='';foreach($W
as$X)$Re.=pack('V',$X);if($ig)return
substr($Re,0,end($W));return$Re;}function
str2long($Re,$ig){$W=array_values(unpack('V*',str_pad($Re,4*ceil(strlen($Re)/4),"\0")));if($ig)$W[]=strlen($Re);return$W;}function
xxtea_mx($ng,$mg,$mf,$Oc){return
int32((($ng>>5&0x7FFFFFF)^$mg<<2)+(($mg>>3&0x1FFFFFFF)^$ng<<4))^int32(($mf^$mg)+($Oc^$ng));}function
encrypt_string($hf,$x){if($hf=="")return"";$x=array_values(unpack("V*",pack("H*",md5($x))));$W=str2long($hf,true);$C=count($W)-1;$ng=$W[$C];$mg=$W[0];$G=floor(6+52/($C+1));$mf=0;while($G-->0){$mf=int32($mf+0x9E3779B9);$Db=$mf>>2&3;for($Xd=0;$Xd<$C;$Xd++){$mg=$W[$Xd+1];$ud=xxtea_mx($ng,$mg,$mf,$x[$Xd&3^$Db]);$ng=int32($W[$Xd]+$ud);$W[$Xd]=$ng;}$mg=$W[0];$ud=xxtea_mx($ng,$mg,$mf,$x[$Xd&3^$Db]);$ng=int32($W[$C]+$ud);$W[$C]=$ng;}return
long2str($W,false);}function
decrypt_string($hf,$x){if($hf=="")return"";$x=array_values(unpack("V*",pack("H*",md5($x))));$W=str2long($hf,false);$C=count($W)-1;$ng=$W[$C];$mg=$W[0];$G=floor(6+52/($C+1));$mf=int32($G*0x9E3779B9);while($mf){$Db=$mf>>2&3;for($Xd=$C;$Xd>0;$Xd--){$ng=$W[$Xd-1];$ud=xxtea_mx($ng,$mg,$mf,$x[$Xd&3^$Db]);$mg=int32($W[$Xd]-$ud);$W[$Xd]=$mg;}$ng=$W[$C];$ud=xxtea_mx($ng,$mg,$mf,$x[$Xd&3^$Db]);$mg=int32($W[0]-$ud);$W[0]=$mg;$mf=int32($mf-0x9E3779B9);}return
long2str($W,true);}$f='';$T=$_SESSION["token"];if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);$he=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($x)=explode(":",$X);$he[$x]=$X;}}$c=$_POST["auth"];if($c){session_regenerate_id();$_SESSION["pwds"][$c["driver"]][$c["server"]][$c["username"]]=$c["password"];$_SESSION["db"][$c["driver"]][$c["server"]][$c["username"]][$c["db"]]=true;if($c["permanent"]){$x=base64_encode($c["driver"])."-".base64_encode($c["server"])."-".base64_encode($c["username"])."-".base64_encode($c["db"]);$re=$b->permanentLogin();$he[$x]="$x:".base64_encode($re?encrypt_string($c["password"],$re):"");cookie("adminer_permanent",implode(" ",$he));}if(count($_POST)==1||DRIVER!=$c["driver"]||SERVER!=$c["server"]||$_GET["username"]!==$c["username"]||DB!=$c["db"])redirect(auth_url($c["driver"],$c["server"],$c["username"],$c["db"]));}elseif($_POST["logout"]){if($T&&$_POST["token"]!=$T){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","db","dbs","queries")as$x)set_session($x,null);unset_permanent();redirect(substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.');}}elseif($he&&!$_SESSION["pwds"]){session_regenerate_id();$re=$b->permanentLogin();foreach($he
as$x=>$X){list(,$Ma)=explode(":",$X);list($yb,$N,$V,$i)=array_map('base64_decode',explode("-",$x));$_SESSION["pwds"][$yb][$N][$V]=decrypt_string(base64_decode($Ma),$re);$_SESSION["db"][$yb][$N][$V][$i]=true;}}function
unset_permanent(){global$he;foreach($he
as$x=>$X){list($yb,$N,$V)=array_map('base64_decode',explode("-",$x));if($yb==DRIVER&&$N==SERVER&&$i==$_GET["username"])unset($he[$x]);}cookie("adminer_permanent",implode(" ",$he));}function
auth_error($Ub=null){global$f,$b,$T;$af=session_name();$j="";if(!$_COOKIE[$af]&&$_GET[$af]&&ini_bool("session.use_only_cookies"))$j='Session support must be enabled.';elseif(isset($_GET["username"])){if(($_COOKIE[$af]||$_GET[$af])&&!$T)$j='Session expired, please login again.';else{$ge=&get_session("pwds");if($ge!==null){$j=h($Ub?$Ub->getMessage():(is_string($f)?$f:'Invalid credentials.'));$ge=null;}unset_permanent();}}page_header('Login',$j,null);echo"<form action='' method='post'>\n";$b->loginForm();echo"<div>";hidden_fields($_POST,array("auth"));echo"</div>\n","</form>\n";page_footer("auth");}if(isset($_GET["username"])){if(!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$me)),false);page_footer("auth");exit;}$f=connect();}if(is_string($f)||!$b->login($_GET["username"],get_session("pwds"))){auth_error();exit;}$T=$_SESSION["token"];if($c&&$_POST["token"])$_POST["token"]=$T;$j=($_POST?($_POST["token"]==$T?"":'Invalid CSRF token. Send the form again.'):($_SERVER["REQUEST_METHOD"]!="POST"?"":sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.','"post_max_size"')));if(!ini_bool("session.use_cookies")||@ini_set("session.use_cookies",false)!==false){session_cache_limiter("");session_write_close();}function
connect_error(){global$b,$f,$T,$j,$zb;$h=array();if(DB!="")page_header('Database'.": ".h(DB),'Invalid database.',true);else{if($_POST["db"]&&!$j)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$j,false);echo"<p><a href='".h(ME)."database='>".'Create new database'."</a>\n";foreach(array('privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$x=>$X){if(support($x))echo"<a href='".h(ME)."$x='>$X</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',$zb[DRIVER],"<b>$f->server_info</b>","<b>$f->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";$De="<a href='".h(ME)."refresh=1'>".'Refresh'."</a>\n";$h=$b->databases();if($h){$Ue=support("scheme");$d=collations();echo"<form action='' method='post'>\n","<table cellspacing='0' class='checkable' onclick='tableClick(event);' ondblclick='tableClick(event, true);'>\n","<thead><tr><td>&nbsp;<th>".'Database'."<td>".'Collation'."<td>".'Tables'."</thead>\n";foreach($h
as$i){$Me=h(ME)."db=".urlencode($i);echo"<tr".odd()."><td>".checkbox("db[]",$i,in_array($i,(array)$_POST["db"])),"<th><a href='$Me'>".h($i)."</a>","<td><a href='$Me".($Ue?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>".nbsp(db_collation($i,$d))."</a>","<td align='right'><a href='$Me&amp;schema=' id='tables-".h($i)."' title='".'Database schema'."'>?</a>","\n";}echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n","<p><input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /db/)").">\n","<input type='hidden' name='token' value='$T'>\n",$De,"</form>\n";}else
echo"<p>$De";}page_footer("db");if($h)echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=connect');</script>\n";}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(!(DB!=""?$f->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}connect_error();exit;}function
select($I,$g=null,$zc="",$Qd=array()){$dd=array();$u=array();$e=array();$Da=array();$Qf=array();$J=array();odd('');for($r=0;$K=$I->fetch_row();$r++){if(!$r){echo"<table cellspacing='0' class='nowrap'>\n","<thead><tr>";for($v=0;$v<count($K);$v++){$k=$I->fetch_field();$D=$k->name;$Pd=$k->orgtable;$Od=$k->orgname;$J[$k->table]=$Pd;if($zc)$dd[$v]=($D=="table"?"table=":($D=="possible_keys"?"indexes=":null));elseif($Pd!=""){if(!isset($u[$Pd])){$u[$Pd]=array();foreach(indexes($Pd,$g)as$t){if($t["type"]=="PRIMARY"){$u[$Pd]=array_flip($t["columns"]);break;}}$e[$Pd]=$u[$Pd];}if(isset($e[$Pd][$Od])){unset($e[$Pd][$Od]);$u[$Pd][$Od]=$v;$dd[$v]=$Pd;}}if($k->charsetnr==63)$Da[$v]=true;$Qf[$v]=$k->type;$D=h($D);echo"<th".($Pd!=""||$k->name!=$Od?" title='".h(($Pd!=""?"$Pd.":"").$Od)."'":"").">".($zc?"<a href='$zc".strtolower($D)."' target='_blank' rel='noreferrer'>$D</a>":$D);}echo"</thead>\n";}echo"<tr".odd().">";foreach($K
as$x=>$X){if($X===null)$X="<i>NULL</i>";elseif($Da[$x]&&!is_utf8($X))$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";elseif(!strlen($X))$X="&nbsp;";else{$X=h($X);if($Qf[$x]==254)$X="<code>$X</code>";}if(isset($dd[$x])&&!$e[$dd[$x]]){if($zc){$R=$K[array_search("table=",$dd)];$z=$dd[$x].urlencode($Qd[$R]!=""?$Qd[$R]:$R);}else{$z="edit=".urlencode($dd[$x]);foreach($u[$dd[$x]]as$Pa=>$v)$z.="&where".urlencode("[".bracket_escape($Pa)."]")."=".urlencode($K[$v]);}$X="<a href='".h(ME.$z)."'>$X</a>";}echo"<td>$X";}}echo($r?"</table>":"<p class='message'>".'No rows.')."\n";return$J;}function
referencable_primary($We){$J=array();foreach(table_status()as$qf=>$R){if($qf!=$We&&fk_support($R)){foreach(fields($qf)as$k){if($k["primary"]){if($J[$qf]){unset($J[$qf]);break;}$J[$qf]=$k;}}}}return$J;}function
textarea($D,$Y,$L=10,$Sa=80){echo"<textarea name='$D' rows='$L' cols='$Sa' class='sqlarea' spellcheck='false' wrap='off' onkeydown='return textareaKeydown(this, event);'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
format_time($ef,$Lb){return" <span class='time'>(".sprintf('%.3f s',max(0,array_sum(explode(" ",$Lb))-array_sum(explode(" ",$ef)))).")</span>";}function
edit_type($x,$k,$d,$n=array()){global$if,$Qf,$Xf,$Gd;echo'<td><select name="',$x,'[type]" class="type" onfocus="lastType = selectValue(this);" onchange="editingTypeChange(this);">',optionlist((!$k["type"]||isset($Qf[$k["type"]])?array():array($k["type"]))+$if+($n?array('Foreign keys'=>$n):array()),$k["type"]),'</select>
<td><input name="',$x,'[length]" value="',h($k["length"]),'" size="3" onfocus="editingLengthFocus(this);"><td class="options">';echo"<select name='$x"."[collation]'".(ereg('(char|text|enum|set)$',$k["type"])?"":" class='hidden'").'><option value="">('.'collation'.')'.optionlist($d,$k["collation"]).'</select>',($Xf?"<select name='$x"."[unsigned]'".(!$k["type"]||ereg('(int|float|double|decimal)$',$k["type"])?"":" class='hidden'").'><option>'.optionlist($Xf,$k["unsigned"]).'</select>':''),($n?"<select name='$x"."[on_delete]'".(ereg("`",$k["type"])?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$Gd),$k["on_delete"])."</select> ":" ");}function
process_length($ad){global$Ob;return(preg_match("~^\\s*(?:$Ob)(?:\\s*,\\s*(?:$Ob))*\\s*\$~",$ad)&&preg_match_all("~$Ob~",$ad,$gd)?implode(",",$gd[0]):preg_replace('~[^0-9,+-]~','',$ad));}function
process_type($k,$Qa="COLLATE"){global$Xf;return" $k[type]".($k["length"]!=""?"(".process_length($k["length"]).")":"").(ereg('int|float|double|decimal',$k["type"])&&in_array($k["unsigned"],$Xf)?" $k[unsigned]":"").(ereg('char|text|enum|set',$k["type"])&&$k["collation"]?" $Qa ".q($k["collation"]):"");}function
process_field($k,$Of){return
array(idf_escape(trim($k["field"])),process_type($Of),($k["null"]?" NULL":" NOT NULL"),(isset($k["default"])?" DEFAULT ".(($k["type"]=="timestamp"&&eregi('^CURRENT_TIMESTAMP$',$k["default"]))||($k["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$k["default"]))?$k["default"]:q($k["default"])):""),($k["on_update"]?" ON UPDATE $k[on_update]":""),(support("comment")&&$k["comment"]!=""?" COMMENT ".q($k["comment"]):""),($k["auto_increment"]?auto_increment():null),);}function
type_class($U){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$x=>$X){if(ereg("$x|$X",$U))return" class='$x'";}}function
edit_fields($l,$d,$U="TABLE",$ra=0,$n=array(),$Xa=false){global$f,$Fc;echo'<thead><tr class="wrap">
';if($U=="PROCEDURE"){echo'<td>&nbsp;';}echo'<th>',($U=="TABLE"?'Column name':'Parameter name'),'<td>Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;" onblur="editingLengthBlur(this);"></textarea>
<td>Length
<td>Options
';if($U=="TABLE"){echo'<td>NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym title="Auto Increment">AI</acronym>
<td>Default values
',(support("comment")?"<td".($Xa?"":" class='hidden'").">".'Comment':"");}echo'<td>',"<input type='image' class='icon' name='add[".(support("move_col")?0:count($l))."]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.6.3' alt='+' title='".'Add next'."'>",'<script type="text/javascript">row_count = ',count($l),';</script>
</thead>
<tbody onkeydown="return editingKeydown(event);">
';foreach($l
as$r=>$k){$r++;$Rd=$k[($_POST?"orig":"field")];$wb=(isset($_POST["add"][$r-1])||(isset($k["field"])&&!$_POST["drop_col"][$r]))&&(support("drop_col")||$Rd=="");echo'<tr',($wb?"":" style='display: none;'"),'>
',($U=="PROCEDURE"?"<td>".html_select("fields[$r][inout]",explode("|",$Fc),$k["inout"]):""),'<th>';if($wb){echo'<input name="fields[',$r,'][field]" value="',h($k["field"]),'" onchange="',($k["field"]!=""||count($l)>1?"":"editingAddRow(this, $ra); "),'editingNameChange(this);" maxlength="64">';}echo'<input type="hidden" name="fields[',$r,'][orig]" value="',h($Rd),'">
';edit_type("fields[$r]",$k,$d,$n);if($U=="TABLE"){echo'<td>',checkbox("fields[$r][null]",1,$k["null"]),'<td><input type="radio" name="auto_increment_col" value="',$r,'"';if($k["auto_increment"]){echo' checked';}?> onclick="var field = this.form['fields[' + this.value + '][field]']; if (!field.value) { field.value = 'id'; field.onchange(); }">
<td><?php echo
checkbox("fields[$r][has_default]",1,$k["has_default"]),'<input name="fields[',$r,'][default]" value="',h($k["default"]),'" onchange="this.previousSibling.checked = true;">
',(support("comment")?"<td".($Xa?"":" class='hidden'")."><input name='fields[$r][comment]' value='".h($k["comment"])."' maxlength='".($f->server_info>=5.5?1024:255)."'>":"");}echo"<td>",(support("move_col")?"<input type='image' class='icon' name='add[$r]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.6.3' alt='+' title='".'Add next'."' onclick='return !editingAddRow(this, $ra, 1);'>&nbsp;"."<input type='image' class='icon' name='up[$r]' src='".h(preg_replace("~\\?.*~","",ME))."?file=up.gif&amp;version=3.6.3' alt='^' title='".'Move up'."'>&nbsp;"."<input type='image' class='icon' name='down[$r]' src='".h(preg_replace("~\\?.*~","",ME))."?file=down.gif&amp;version=3.6.3' alt='v' title='".'Move down'."'>&nbsp;":""),($Rd==""||support("drop_col")?"<input type='image' class='icon' name='drop_col[$r]' src='".h(preg_replace("~\\?.*~","",ME))."?file=cross.gif&amp;version=3.6.3' alt='x' title='".'Remove'."' onclick='return !editingRemoveRow(this);'>":""),"\n";}}function
process_fields(&$l){ksort($l);$Ad=0;if($_POST["up"]){$Uc=0;foreach($l
as$x=>$k){if(key($_POST["up"])==$x){unset($l[$x]);array_splice($l,$Uc,0,array($k));break;}if(isset($k["field"]))$Uc=$Ad;$Ad++;}}if($_POST["down"]){$mc=false;foreach($l
as$x=>$k){if(isset($k["field"])&&$mc){unset($l[key($_POST["down"])]);array_splice($l,$Ad,0,array($mc));break;}if(key($_POST["down"])==$x)$mc=$k;$Ad++;}}$l=array_values($l);if($_POST["add"])array_splice($l,key($_POST["add"]),0,array(array()));}function
normalize_enum($A){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($A[0][0].$A[0][0],$A[0][0],substr($A[0],1,-1))),'\\'))."'";}function
grant($q,$te,$e,$Fd){if(!$te)return
true;if($te==array("ALL PRIVILEGES","GRANT OPTION"))return($q=="GRANT"?queries("$q ALL PRIVILEGES$Fd WITH GRANT OPTION"):queries("$q ALL PRIVILEGES$Fd")&&queries("$q GRANT OPTION$Fd"));return
queries("$q ".preg_replace('~(GRANT OPTION)\\([^)]*\\)~','\\1',implode("$e, ",$te).$e).$Fd);}function
drop_create($_b,$fb,$_,$pd,$nd,$od,$D){if($_POST["drop"])return
query_redirect($_b,$_,$pd,true,!$_POST["dropped"]);$Ab=$D!=""&&($_POST["dropped"]||queries($_b));$hb=queries($fb);if(!queries_redirect($_,($D!=""?$nd:$od),$hb)&&$Ab)redirect(null,$pd);return$Ab;}function
remove_definer($H){return
preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\\1)',logged_user()).'`~','\\1',$H);}function
tar_file($fc,$bb){$J=pack("a100a8a8a8a12a12",$fc,644,0,0,decoct(strlen($bb)),decoct(time()));$La=8*32;for($r=0;$r<strlen($J);$r++)$La+=ord($J[$r]);$J.=sprintf("%06o",$La)."\0 ";return$J.str_repeat("\0",512-strlen($J)).$bb.str_repeat("\0",511-(strlen($bb)+511)%512);}function
ini_bytes($Ec){$X=ini_get($Ec);switch(strtolower(substr($X,-1))){case'g':$X*=1024;case'm':$X*=1024;case'k':$X*=1024;}return$X;}$Gd="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";$Ob="'(?:''|[^'\\\\]|\\\\.)*+'";$Fc="IN|OUT|INOUT";if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$l=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));echo$f->result("SELECT".limit(idf_escape($_GET["field"])." FROM ".table($a)," WHERE ".where($_GET,$l),1));exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$l=fields($a);if(!$l)$j=error();$S=($l?table_status($a):array());page_header(($l&&is_view($S)?'View':'Table').": ".h($a),$j);$b->selectLinks($S);$Wa=$S["Comment"];if($Wa!="")echo"<p>".'Comment'.": ".h($Wa)."\n";if($l){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";foreach($l
as$k){echo"<tr".odd()."><th>".h($k["field"]),"<td title='".h($k["collation"])."'>".h($k["full_type"]).($k["null"]?" <i>NULL</i>":"").($k["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($k["default"])?" [<b>".h($k["default"])."</b>]":""),(support("comment")?"<td>".nbsp($k["comment"]):""),"\n";}echo"</table>\n";if(!is_view($S)){echo"<h3>".'Indexes'."</h3>\n";$u=indexes($a);if($u){echo"<table cellspacing='0'>\n";foreach($u
as$D=>$t){ksort($t["columns"]);$qe=array();foreach($t["columns"]as$x=>$X)$qe[]="<i>".h($X)."</i>".($t["lengths"][$x]?"(".$t["lengths"][$x].")":"");echo"<tr title='".h($D)."'><th>$t[type]<td>".implode(", ",$qe)."\n";}echo"</table>\n";}echo'<p><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";if(fk_support($S)){echo"<h3>".'Foreign keys'."</h3>\n";$n=foreign_keys($a);if($n){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'.($w!="sqlite"?"<td>&nbsp;":"")."</thead>\n";foreach($n
as$D=>$m){echo"<tr title='".h($D)."'>","<th><i>".implode("</i>, <i>",array_map('h',$m["source"]))."</i>","<td><a href='".h($m["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($m["db"]),ME):($m["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($m["ns"]),ME):ME))."table=".urlencode($m["table"])."'>".($m["db"]!=""?"<b>".h($m["db"])."</b>.":"").($m["ns"]!=""?"<b>".h($m["ns"])."</b>.":"").h($m["table"])."</a>","(<i>".implode("</i>, <i>",array_map('h',$m["target"]))."</i>)","<td>".nbsp($m["on_delete"])."\n","<td>".nbsp($m["on_update"])."\n",($w=="sqlite"?"":'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($D)).'">'.'Alter'.'</a>');}echo"</table>\n";}if($w!="sqlite")echo'<p><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}if(support("trigger")){echo"<h3>".'Triggers'."</h3>\n";$Nf=triggers($a);if($Nf){echo"<table cellspacing='0'>\n";foreach($Nf
as$x=>$X)echo"<tr valign='top'><td>$X[0]<td>$X[1]<th>".h($x)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($x))."'>".'Alter'."</a>\n";echo"</table>\n";}echo'<p><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),DB.($_GET["ns"]?".$_GET[ns]":""));$rf=array();$sf=array();$D="adminer_schema";$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE[($_COOKIE["$D-".DB]?"$D-".DB:$D)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$gd,PREG_SET_ORDER);foreach($gd
as$r=>$A){$rf[$A[1]]=array($A[2],$A[3]);$sf[]="\n\t'".js_escape($A[1])."': [ $A[2], $A[3] ]";}$Gf=0;$Aa=-1;$Te=array();$Ce=array();$Yc=array();foreach(table_status()as$S){if(!isset($S["Engine"]))continue;$je=0;$Te[$S["Name"]]["fields"]=array();foreach(fields($S["Name"])as$D=>$k){$je+=1.25;$k["pos"]=$je;$Te[$S["Name"]]["fields"][$D]=$k;}$Te[$S["Name"]]["pos"]=($rf[$S["Name"]]?$rf[$S["Name"]]:array($Gf,0));foreach($b->foreignKeys($S["Name"])as$X){if(!$X["db"]){$Wc=$Aa;if($rf[$S["Name"]][1]||$rf[$X["table"]][1])$Wc=min(floatval($rf[$S["Name"]][1]),floatval($rf[$X["table"]][1]))-1;else$Aa-=.1;while($Yc[(string)$Wc])$Wc-=.0001;$Te[$S["Name"]]["references"][$X["table"]][(string)$Wc]=array($X["source"],$X["target"]);$Ce[$X["table"]][$S["Name"]][(string)$Wc]=$X["target"];$Yc[(string)$Wc]=true;}}$Gf=max($Gf,$Te[$S["Name"]]["pos"][0]+2.5+$je);}echo'<div id="schema" style="height: ',$Gf,'em;" onselectstart="return false;">
<script type="text/javascript">
var tablePos = {',implode(",",$sf)."\n",'};
var em = document.getElementById(\'schema\').offsetHeight / ',$Gf,';
document.onmousemove = schemaMousemove;
document.onmouseup = function (ev) {
	schemaMouseup(ev, \'',js_escape(DB),'\');
};
</script>
';foreach($Te
as$D=>$R){echo"<div class='table' style='top: ".$R["pos"][0]."em; left: ".$R["pos"][1]."em;' onmousedown='schemaMousedown(this, event);'>",'<a href="'.h(ME).'table='.urlencode($D).'"><b>'.h($D)."</b></a>";foreach($R["fields"]as$k){$X='<span'.type_class($k["type"]).' title="'.h($k["full_type"].($k["null"]?" NULL":'')).'">'.h($k["field"]).'</span>';echo"<br>".($k["primary"]?"<i>$X</i>":$X);}foreach((array)$R["references"]as$yf=>$Ee){foreach($Ee
as$Wc=>$_e){$Xc=$Wc-$rf[$D][1];$r=0;foreach($_e[0]as$bf)echo"\n<div class='references' title='".h($yf)."' id='refs$Wc-".($r++)."' style='left: $Xc"."em; top: ".$R["fields"][$bf]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$Xc)."em;'></div></div>";}}foreach((array)$Ce[$D]as$yf=>$Ee){foreach($Ee
as$Wc=>$e){$Xc=$Wc-$rf[$D][1];$r=0;foreach($e
as$xf)echo"\n<div class='references' title='".h($yf)."' id='refd$Wc-".($r++)."' style='left: $Xc"."em; top: ".$R["fields"][$xf]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME))."?file=arrow.gif) no-repeat right center;&amp;version=3.6.3'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$Xc)."em;'></div></div>";}}echo"\n</div>\n";}foreach($Te
as$D=>$R){foreach((array)$R["references"]as$yf=>$Ee){foreach($Ee
as$Wc=>$_e){$rd=$Gf;$kd=-10;foreach($_e[0]as$x=>$bf){$ke=$R["pos"][0]+$R["fields"][$bf]["pos"];$le=$Te[$yf]["pos"][0]+$Te[$yf]["fields"][$_e[1][$x]]["pos"];$rd=min($rd,$ke,$le);$kd=max($kd,$ke,$le);}echo"<div class='references' id='refl$Wc' style='left: $Wc"."em; top: $rd"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($kd-$rd)."em;'></div></div>\n";}}}echo'</div>
<p><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST){$db="";foreach(array("output","format","db_style","routines","events","table_style","auto_increment","triggers","data_style")as$x)$db.="&$x=".urlencode($_POST[$x]);cookie("adminer_export",substr($db,1));$ac=dump_headers(($a!=""?$a:DB),(DB==""||count((array)$_POST["tables"]+(array)$_POST["data"])>1));$Kc=($_POST["format"]=="sql");if($Kc)echo"-- Adminer $ga ".$zb[DRIVER]." dump

".($w!="sql"?"":"SET NAMES utf8;
".($_POST["data_style"]?"SET foreign_key_checks = 0;
SET time_zone = ".q($f->result("SELECT @@time_zone")).";
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
");$Q=$_POST["db_style"];$h=array(DB);if(DB==""){$h=$_POST["databases"];if(is_string($h))$h=explode("\n",rtrim(str_replace("\r","",$h),"\n"));}foreach((array)$h
as$i){if($f->select_db($i)){if($Kc&&ereg('CREATE',$Q)&&($fb=$f->result("SHOW CREATE DATABASE ".idf_escape($i),1))){if($Q=="DROP+CREATE")echo"DROP DATABASE IF EXISTS ".idf_escape($i).";\n";echo($Q=="CREATE+ALTER"?preg_replace('~^CREATE DATABASE ~','\\0IF NOT EXISTS ',$fb):$fb).";\n";}if($Kc){if($Q)echo
use_sql($i).";\n\n";if(in_array("CREATE+ALTER",array($Q,$_POST["table_style"])))echo"SET @adminer_alter = '';\n\n";$Vd="";if($_POST["routines"]){foreach(array("FUNCTION","PROCEDURE")as$Ne){foreach(get_rows("SHOW $Ne STATUS WHERE Db = ".q($i),null,"-- ")as$K)$Vd.=($Q!='DROP+CREATE'?"DROP $Ne IF EXISTS ".idf_escape($K["Name"]).";;\n":"").remove_definer($f->result("SHOW CREATE $Ne ".idf_escape($K["Name"]),2)).";;\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$K)$Vd.=($Q!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($K["Name"]).";;\n":"").remove_definer($f->result("SHOW CREATE EVENT ".idf_escape($K["Name"]),3)).";;\n\n";}if($Vd)echo"DELIMITER ;;\n\n$Vd"."DELIMITER ;\n\n";}if($_POST["table_style"]||$_POST["data_style"]){$gg=array();foreach(table_status()as$S){$R=(DB==""||in_array($S["Name"],(array)$_POST["tables"]));$kb=(DB==""||in_array($S["Name"],(array)$_POST["data"]));if($R||$kb){if(!is_view($S)){if($ac=="tar")ob_start();$b->dumpTable($S["Name"],($R?$_POST["table_style"]:""));if($kb)$b->dumpData($S["Name"],$_POST["data_style"],"SELECT * FROM ".table($S["Name"]));if($Kc&&$_POST["triggers"]&&$R&&($Nf=trigger_sql($S["Name"],$_POST["table_style"])))echo"\nDELIMITER ;;\n$Nf\nDELIMITER ;\n";if($ac=="tar")echo
tar_file((DB!=""?"":"$i/")."$S[Name].csv",ob_get_clean());elseif($Kc)echo"\n";}elseif($Kc)$gg[]=$S["Name"];}}foreach($gg
as$fg)$b->dumpTable($fg,$_POST["table_style"],true);if($ac=="tar")echo
pack("x512");}if($Q=="CREATE+ALTER"&&$Kc){$H="SELECT TABLE_NAME, ENGINE, TABLE_COLLATION, TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()";echo"DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _table_name, _engine, _table_collation varchar(64);
	DECLARE _table_comment varchar(64);
	DECLARE done bool DEFAULT 0;
	DECLARE tables CURSOR FOR $H;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN tables;
	REPEAT
		FETCH tables INTO _table_name, _engine, _table_collation, _table_comment;
		IF NOT done THEN
			CASE _table_name";foreach(get_rows($H)as$K){$Wa=q($K["ENGINE"]=="InnoDB"?preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["TABLE_COMMENT"]):$K["TABLE_COMMENT"]);echo"
				WHEN ".q($K["TABLE_NAME"])." THEN
					".(isset($K["ENGINE"])?"IF _engine != '$K[ENGINE]' OR _table_collation != '$K[TABLE_COLLATION]' OR _table_comment != $Wa THEN
						ALTER TABLE ".idf_escape($K["TABLE_NAME"])." ENGINE=$K[ENGINE] COLLATE=$K[TABLE_COLLATION] COMMENT=$Wa;
					END IF":"BEGIN END").";";}echo"
				ELSE
					SET alter_command = CONCAT(alter_command, 'DROP TABLE `', REPLACE(_table_name, '`', '``'), '`;\\n');
			END CASE;
		END IF;
	UNTIL done END REPEAT;
	CLOSE tables;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;
";}if(in_array("CREATE+ALTER",array($Q,$_POST["table_style"]))&&$Kc)echo"SELECT @adminer_alter;\n";}}if($Kc)echo"-- ".$f->result("SELECT NOW()")."\n";exit;}page_header('Export',"",($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),DB);echo'
<form action="" method="post">
<table cellspacing="0">
';$nb=array('','USE','DROP+CREATE','CREATE');$tf=array('','DROP+CREATE','CREATE');$lb=array('','TRUNCATE+INSERT','INSERT');if($w=="sql"){$nb[]='CREATE+ALTER';$tf[]='CREATE+ALTER';$lb[]='INSERT+UPDATE';}parse_str($_COOKIE["adminer_export"],$K);if(!$K)$K=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");if(!isset($K["events"])){$K["routines"]=$K["events"]=($_GET["dump"]=="");$K["triggers"]=$K["table_style"];}echo"<tr><th>".'Output'."<td>".html_select("output",$b->dumpOutput(),$K["output"],0)."\n";echo"<tr><th>".'Format'."<td>".html_select("format",$b->dumpFormat(),$K["format"],0)."\n";echo($w=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$nb,$K["db_style"]).(support("routine")?checkbox("routines",1,$K["routines"],'Routines'):"").(support("event")?checkbox("events",1,$K["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$tf,$K["table_style"]).checkbox("auto_increment",1,$K["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$K["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$lb,$K["data_style"]),'</table>
<p><input type="submit" value="Export">

<table cellspacing="0">
';$oe=array();if(DB!=""){$Ka=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label><input type='checkbox' id='check-tables'$Ka onclick='formCheck(this, /^tables\\[/);'>".'Tables'."</label>","<th style='text-align: right;'><label>".'Data'."<input type='checkbox' id='check-data'$Ka onclick='formCheck(this, /^data\\[/);'></label>","</thead>\n";$gg="";foreach(table_status()as$S){$D=$S["Name"];$ne=ereg_replace("_.*","",$D);$Ka=($a==""||$a==(substr($a,-1)=="%"?"$ne%":$D));$qe="<tr><td>".checkbox("tables[]",$D,$Ka,$D,"checkboxClick(event, this); formUncheck('check-tables');");if(is_view($S))$gg.="$qe\n";else
echo"$qe<td align='right'><label>".($S["Engine"]=="InnoDB"&&$S["Rows"]?"~ ":"").$S["Rows"].checkbox("data[]",$D,$Ka,"","checkboxClick(event, this); formUncheck('check-data');")."</label>\n";$oe[$ne]++;}echo$gg;}else{echo"<thead><tr><th style='text-align: left;'><label><input type='checkbox' id='check-databases'".($a==""?" checked":"")." onclick='formCheck(this, /^databases\\[/);'>".'Database'."</label></thead>\n";$h=$b->databases();if($h){foreach($h
as$i){if(!information_schema($i)){$ne=ereg_replace("_.*","",$i);echo"<tr><td>".checkbox("databases[]",$i,$a==""||$a=="$ne%",$i,"formUncheck('check-databases');")."</label>\n";$oe[$ne]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$hc=true;foreach($oe
as$x=>$X){if($x!=""&&$X>1){echo($hc?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$x%")."'>".h($x)."</a>";$hc=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');$I=$f->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$q=$I;if(!$I)$I=$f->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($q?"":"<input type='hidden' name='grant' value=''>\n"),"<table cellspacing='0'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th>&nbsp;</thead>\n";while($K=$I->fetch_assoc())echo'<tr'.odd().'><td>'.h($K["User"])."<td>".h($K["Host"]).'<td><a href="'.h(ME.'user='.urlencode($K["User"]).'&host='.urlencode($K["Host"])).'">'.'Edit'."</a>\n";if(!$q||DB!="")echo"<tr".odd()."><td><input name='user'><td><input name='host' value='localhost'><td><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n",'<p><a href="'.h(ME).'user=">'.'Create user'."</a>";}elseif(isset($_GET["sql"])){if(!$j&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);exit;}restart_session();$xc=&get_session("queries");$wc=&$xc[DB];if(!$j&&$_POST["clear"]){$wc=array();redirect(remove_from_uri("history"));}page_header('SQL command',$j);if(!$j&&$_POST){$oc=false;$H=$_POST["query"];if($_POST["webfile"]){$oc=@fopen((file_exists("adminer.sql")?"adminer.sql":(file_exists("adminer.sql.gz")?"compress.zlib://adminer.sql.gz":"compress.bzip2://adminer.sql.bz2")),"rb");$H=($oc?fread($oc,1e6):false);}elseif($_FILES&&$_FILES["sql_file"]["error"]!=UPLOAD_ERR_NO_FILE)$H=get_file("sql_file",true);if(is_string($H)){if(function_exists('memory_get_usage'))@ini_set("memory_limit",max(ini_bytes("memory_limit"),2*strlen($H)+memory_get_usage()+8e6));if($H!=""&&strlen($H)<1e6){$G=$H.(ereg(";[ \t\r\n]*\$",$H)?"":";");if(!$wc||reset(end($wc))!=$G){restart_session();$wc[]=array($G,time());set_session("queries",$xc);stop_session();}}$cf="(?:\\s|/\\*.*\\*/|(?:#|-- )[^\n]*\n|--\n)";$rb=";";$Ad=0;$Kb=true;$g=connect();if(is_object($g)&&DB!="")$g->select_db(DB);$Va=0;$Qb=array();$cd=0;$ae='[\'"'.($w=="sql"?'`#':($w=="sqlite"?'`[':($w=="mssql"?'[':''))).']|/\\*|-- |$'.($w=="pgsql"?'|\\$[^$]*\\$':'');$Hf=microtime();parse_str($_COOKIE["adminer_export"],$ka);$Cb=$b->dumpFormat();unset($Cb["sql"]);while($H!=""){if(!$Ad&&preg_match("~^$cf*DELIMITER\\s+(\\S+)~i",$H,$A)){$rb=$A[1];$H=substr($H,strlen($A[0]));}else{preg_match('('.preg_quote($rb)."\\s*|$ae)",$H,$A,PREG_OFFSET_CAPTURE,$Ad);list($mc,$je)=$A[0];if(!$mc&&$oc&&!feof($oc))$H.=fread($oc,1e5);else{if(!$mc&&rtrim($H)=="")break;$Ad=$je+strlen($mc);if($mc&&rtrim($mc)!=$rb){while(preg_match('('.($mc=='/*'?'\\*/':($mc=='['?']':(ereg('^-- |^#',$mc)?"\n":preg_quote($mc)."|\\\\."))).'|$)s',$H,$A,PREG_OFFSET_CAPTURE,$Ad)){$Re=$A[0][0];if(!$Re&&$oc&&!feof($oc))$H.=fread($oc,1e5);else{$Ad=$A[0][1]+strlen($Re);if($Re[0]!="\\")break;}}}else{$Kb=false;$G=substr($H,0,$je);$Va++;$qe="<pre id='sql-$Va'><code class='jush-$w'>".shorten_utf8(trim($G),1000)."</code></pre>\n";if(!$_POST["only_errors"]){echo$qe;ob_flush();flush();}$ef=microtime();if($f->multi_query($G)&&is_object($g)&&preg_match("~^$cf*USE\\b~isU",$G))$g->query($G);do{$I=$f->store_result();$Lb=microtime();$Af=format_time($ef,$Lb).(strlen($G)<1000?" <a href='".h(ME)."sql=".urlencode(trim($G))."'>".'Edit'."</a>":"");if($f->error){echo($_POST["only_errors"]?$qe:""),"<p class='error'>".'Error in query'.($f->errno?" ($f->errno)":"").": ".error()."\n";$Qb[]=" <a href='#sql-$Va'>$Va</a>";if($_POST["error_stops"])break
2;}elseif(is_object($I)){$Qd=select($I,$g);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n","<p>".($I->num_rows?lang(array('%d row','%d rows'),$I->num_rows):"").$Af;$s="export-$Va";$Zb=", <a href='#$s' onclick=\"return !toggle('$s');\">".'Export'."</a><span id='$s' class='hidden'>: ".html_select("output",$b->dumpOutput(),$ka["output"])." ".html_select("format",$Cb,$ka["format"])."<input type='hidden' name='query' value='".h($G)."'>"." <input type='submit' name='export' value='".'Export'."'><input type='hidden' name='token' value='$T'></span>\n";if($g&&preg_match("~^($cf|\\()*SELECT\\b~isU",$G)&&($Yb=explain($g,$G))){$s="explain-$Va";echo", <a href='#$s' onclick=\"return !toggle('$s');\">EXPLAIN</a>$Zb","<div id='$s' class='hidden'>\n";select($Yb,$g,($w=="sql"?"http://dev.mysql.com/doc/refman/".substr($f->server_info,0,3)."/en/explain-output.html#explain_":""),$Qd);echo"</div>\n";}else
echo$Zb;echo"</form>\n";}}else{if(preg_match("~^$cf*(CREATE|DROP|ALTER)$cf+(DATABASE|SCHEMA)\\b~isU",$G)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h($f->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$f->affected_rows)."$Af\n";}$ef=$Lb;}while($f->next_result());$cd+=substr_count($G.$mc,"\n");$H=substr($H,$Ad);$Ad=0;}}}}if($Kb)echo"<p class='message'>".'No commands to execute.'."\n";elseif($_POST["only_errors"])echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$Va-count($Qb)).format_time($Hf,microtime())."\n";elseif($Qb&&$Va>1)echo"<p class='error'>".'Error in query'.": ".implode("",$Qb)."\n";}else
echo"<p class='error'>".upload_error($H)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
<p>';$G=$_GET["sql"];if($_POST)$G=$_POST["query"];elseif($_GET["history"]=="all")$G=$wc;elseif($_GET["history"]!="")$G=$wc[$_GET["history"]][0];textarea("query",$G,20);echo($_POST?"":"<script type='text/javascript'>document.getElementsByTagName('textarea')[0].focus();</script>\n"),"<p>".(ini_bool("file_uploads")?'File upload'.': <input type="file" name="sql_file"'.($_FILES&&$_FILES["sql_file"]["error"]!=4?'':' onchange="this.form[\'only_errors\'].checked = true;"').'> (&lt; '.ini_get("upload_max_filesize").'B)':'File uploads are disabled.'),'<p>
<input type="submit" value="Execute" title="Ctrl+Enter">
<input type="hidden" name="token" value="',$T,'">
',checkbox("error_stops",1,$_POST["error_stops"],'Stop on error')."\n",checkbox("only_errors",1,$_POST["only_errors"],'Show only errors')."\n";print_fieldset("webfile",'From server',$_POST["webfile"],"document.getElementById('form')['only_errors'].checked = true; ");$Ya=array();foreach(array("gz"=>"zlib","bz2"=>"bz2")as$x=>$X){if(extension_loaded($X))$Ya[]=".$x";}echo
sprintf('Webserver file %s',"<code>adminer.sql".($Ya?"[".implode("|",$Ya)."]":"")."</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";if($wc){print_fieldset("history",'History',$_GET["history"]!="");foreach($wc
as$x=>$X){list($G,$Af)=$X;echo'<a href="'.h(ME."sql=&history=$x").'">'.'Edit'."</a> <span class='time' title='".@date('Y-m-d',$Af)."'>".@date("H:i:s",$Af)."</span> <code class='jush-$w'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$G)))),80,"</code>")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'
</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$l=fields($a);$Z=(isset($_GET["select"])?(count($_POST["check"])==1?where_check($_POST["check"][0],$l):""):where($_GET,$l));$Yf=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($l
as$D=>$k){if(!isset($k["privileges"][$Yf?"update":"insert"])||$b->fieldName($k)=="")unset($l[$D]);}if($_POST&&!$j&&!isset($_GET["select"])){$_=$_POST["referer"];if($_POST["insert"])$_=($Yf?null:$_SERVER["REQUEST_URI"]);elseif(!ereg('^.+&select=.+$',$_))$_=ME."select=".urlencode($a);if(isset($_POST["delete"]))query_redirect("DELETE".limit1("FROM ".table($a)," WHERE $Z"),$_,'Item has been deleted.');else{$O=array();foreach($l
as$D=>$k){$X=process_input($k);if($X!==false&&$X!==null)$O[idf_escape($D)]=($Yf?"\n".idf_escape($D)." = $X":$X);}if($Yf){if(!$O)redirect($_);query_redirect("UPDATE".limit1(table($a)." SET".implode(",",$O),"\nWHERE $Z"),$_,'Item has been updated.');}else{$I=insert_into($a,$O);$Vc=($I?last_id():0);queries_redirect($_,sprintf('Item%s has been inserted.',($Vc?" $Vc":"")),$I);}}}$qf=$b->tableName(table_status($a));page_header(($Yf?'Edit':'Insert'),$j,array("select"=>array($a,$qf)),$qf);$K=null;if($_POST["save"])$K=(array)$_POST["fields"];elseif($Z){$M=array();foreach($l
as$D=>$k){if(isset($k["privileges"]["select"])){$ta=convert_field($k);if($_POST["clone"]&&$k["auto_increment"])$ta="''";if($w=="sql"&&ereg("enum|set",$k["type"]))$ta="1*".idf_escape($D);$M[]=($ta?"$ta AS ":"").idf_escape($D);}}$K=array();if($M){$L=get_rows("SELECT".limit(implode(", ",$M)." FROM ".table($a)," WHERE $Z",(isset($_GET["select"])?2:1)));$K=(isset($_GET["select"])&&count($L)!=1?null:reset($L));}}if($K===false)echo"<p class='error'>".'No rows.'."\n";echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$l)echo"<p class='error'>".'You have no privileges to update this table.'."\n";else{echo"<table cellspacing='0' onkeydown='return editingKeydown(event);'>\n";foreach($l
as$D=>$k){echo"<tr><th>".$b->fieldName($k);$qb=$_GET["set"][bracket_escape($D)];$Y=($K!==null?($K[$D]!=""&&$w=="sql"&&ereg("enum|set",$k["type"])?(is_array($K[$D])?array_sum($K[$D]):+$K[$D]):$K[$D]):(!$Yf&&$k["auto_increment"]?"":(isset($_GET["select"])?false:($qb!==null?$qb:$k["default"]))));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$k);$o=($_POST["save"]?(string)$_POST["function"][$D]:($Yf&&$k["on_update"]=="CURRENT_TIMESTAMP"?"now":($Y===false?null:($Y!==null?'':'NULL'))));if($k["type"]=="timestamp"&&$Y=="CURRENT_TIMESTAMP"){$Y="";$o="now";}input($k,$Y,$o);echo"\n";}echo"</table>\n";}echo'<p>
';if($l){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"]))echo"<input type='submit' name='insert' value='".($Yf?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n";}echo($Yf?"<input type='submit' name='delete' value='".'Delete'."' onclick=\"return confirm('".'Are you sure?'."');\">\n":($_POST||!$l?"":"<script type='text/javascript'>document.getElementById('form').getElementsByTagName('td')[1].firstChild.focus();</script>\n"));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["create"])){$a=$_GET["create"];$be=array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST');$Be=referencable_primary($a);$n=array();foreach($Be
as$qf=>$k)$n[str_replace("`","``",$qf)."`".str_replace("`","``",$k["field"])]=$qf;$Td=array();$Ud=array();if($a!=""){$Td=fields($a);$Ud=table_status($a);}if($_POST&&!$_POST["fields"])$_POST["fields"]=array();if($_POST&&!$j&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){if($_POST["drop"])query_redirect("DROP TABLE ".table($a),substr(ME,0,-1),'Table has been dropped.');else{$l=array();$qa=array();$ag=false;$jc=array();ksort($_POST["fields"]);$Sd=reset($Td);$oa=" FIRST";foreach($_POST["fields"]as$x=>$k){$m=$n[$k["type"]];$Of=($m!==null?$Be[$m]:$k);if($k["field"]!=""){if(!$k["has_default"])$k["default"]=null;$qb=eregi_replace(" *on update CURRENT_TIMESTAMP","",$k["default"]);if($qb!=$k["default"]){$k["on_update"]="CURRENT_TIMESTAMP";$k["default"]=$qb;}if($x==$_POST["auto_increment_col"])$k["auto_increment"]=true;$ve=process_field($k,$Of);$qa[]=array($k["orig"],$ve,$oa);if($ve!=process_field($Sd,$Sd)){$l[]=array($k["orig"],$ve,$oa);if($k["orig"]!=""||$oa)$ag=true;}if($m!==null)$jc[idf_escape($k["field"])]=($a!=""&&$w!="sqlite"?"ADD":" ")." FOREIGN KEY (".idf_escape($k["field"]).") REFERENCES ".table($n[$k["type"]])." (".idf_escape($Of["field"]).")".(ereg("^($Gd)\$",$k["on_delete"])?" ON DELETE $k[on_delete]":"");$oa=" AFTER ".idf_escape($k["field"]);}elseif($k["orig"]!=""){$ag=true;$l[]=array($k["orig"]);}if($k["orig"]!=""){$Sd=next($Td);if(!$Sd)$oa="";}}$de="";if(in_array($_POST["partition_by"],$be)){$ee=array();if($_POST["partition_by"]=='RANGE'||$_POST["partition_by"]=='LIST'){foreach(array_filter($_POST["partition_names"])as$x=>$X){$Y=$_POST["partition_values"][$x];$ee[]="\nPARTITION ".idf_escape($X)." VALUES ".($_POST["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$de.="\nPARTITION BY $_POST[partition_by]($_POST[partition])".($ee?" (".implode(",",$ee)."\n)":($_POST["partitions"]?" PARTITIONS ".(+$_POST["partitions"]):""));}elseif(support("partitioning")&&ereg("partitioned",$Ud["Create_options"]))$de.="\nREMOVE PARTITIONING";$B='Table has been altered.';if($a==""){cookie("adminer_engine",$_POST["Engine"]);$B='Table has been created.';}$D=trim($_POST["name"]);queries_redirect(ME."table=".urlencode($D),$B,alter_table($a,$D,($w=="sqlite"&&($ag||$jc)?$qa:$l),$jc,$_POST["Comment"],($_POST["Engine"]&&$_POST["Engine"]!=$Ud["Engine"]?$_POST["Engine"]:""),($_POST["Collation"]&&$_POST["Collation"]!=$Ud["Collation"]?$_POST["Collation"]:""),($_POST["Auto_increment"]!=""?+$_POST["Auto_increment"]:""),$de));}}page_header(($a!=""?'Alter table':'Create table'),$j,array("table"=>$a),$a);$K=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($Qf["int"])?"int":(isset($Qf["integer"])?"integer":"")))),"partition_names"=>array(""),);if($_POST){$K=$_POST;if($K["auto_increment_col"])$K["fields"][$K["auto_increment_col"]]["auto_increment"]=true;process_fields($K["fields"]);}elseif($a!=""){$K=$Ud;$K["name"]=$a;$K["fields"]=array();if(!$_GET["auto_increment"])$K["Auto_increment"]="";foreach($Td
as$k){$k["has_default"]=isset($k["default"]);if($k["on_update"])$k["default"].=" ON UPDATE $k[on_update]";$K["fields"][]=$k;}if(support("partitioning")){$pc="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);$I=$f->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $pc ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");list($K["partition_by"],$K["partitions"],$K["partition"])=$I->fetch_row();$K["partition_names"]=array();$K["partition_values"]=array();foreach(get_rows("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $pc AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION")as$Qe){$K["partition_names"][]=$Qe["PARTITION_NAME"];$K["partition_values"][]=$Qe["PARTITION_DESCRIPTION"];}$K["partition_names"][]="";}}$d=collations();$lf=floor(extension_loaded("suhosin")?(min(ini_get("suhosin.request.max_vars"),ini_get("suhosin.post.max_vars"))-13)/10:0);if($lf&&count($K["fields"])>$lf)echo"<p class='error'>".h(sprintf('Maximum number of allowed fields exceeded. Please increase %s and %s.','suhosin.post.max_vars','suhosin.request.max_vars'))."\n";$Nb=engines();foreach($Nb
as$Mb){if(!strcasecmp($Mb,$K["Engine"])){$K["Engine"]=$Mb;break;}}echo'
<form action="" method="post" id="form">
<p>
Table name: <input name="name" maxlength="64" value="',h($K["name"]),'">
';if($a==""&&!$_POST){?><script type='text/javascript'>document.getElementById('form')['name'].focus();</script><?php }echo($Nb?html_select("Engine",array(""=>"(".'engine'.")")+$Nb,$K["Engine"]):""),' ',($d&&!ereg("sqlite|mssql",$w)?html_select("Collation",array(""=>"(".'collation'.")")+$d,$K["Collation"]):""),' <input type="submit" value="Save">
<table cellspacing="0" id="edit-fields" class="nowrap">
';$Xa=($_POST?$_POST["comments"]:$K["Comment"]!="");if(!$_POST&&!$Xa){foreach($K["fields"]as$k){if($k["comment"]!=""){$Xa=true;break;}}}edit_fields($K["fields"],$d,"TABLE",$lf,$n,$Xa);echo'</table>
<p>
Auto Increment: <input name="Auto_increment" size="6" value="',h($K["Auto_increment"]),'">
<label class="jsonly"><input type="checkbox" id="defaults" name="defaults" value="1" checked onclick="columnShow(this.checked, 5);">Default values</label>
';if(!$_POST["defaults"]){echo'<script type="text/javascript">editingHideDefaults()</script>';}echo(support("comment")?checkbox("comments",1,$Xa,'Comment',"columnShow(this.checked, 6); toggle('Comment'); if (this.checked) this.form['Comment'].focus();",true).' <input id="Comment" name="Comment" value="'.h($K["Comment"]).'" maxlength="'.($f->server_info>=5.5?2048:60).'"'.($Xa?'':' class="hidden"').'>':''),'<p>
<input type="submit" value="Save">
';if($_GET["create"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$T,'">
';if(support("partitioning")){$ce=ereg('RANGE|LIST',$K["partition_by"]);print_fieldset("partition",'Partition by',$K["partition_by"]);echo'<p>
',html_select("partition_by",array(-1=>"")+$be,$K["partition_by"],"partitionByChange(this);"),'(<input name="partition" value="',h($K["partition"]),'">)
Partitions: <input type="number" name="partitions" class="size" value="',h($K["partitions"]),'"',($ce||!$K["partition_by"]?" class='hidden'":""),'>
<table cellspacing="0" id="partition-table"',($ce?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($K["partition_names"]as$x=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'"'.($x==count($K["partition_names"])-1?' onchange="partitionNameChange(this);"':'').'>','<td><input name="partition_values[]" value="'.h($K["partition_values"][$x]).'">';}echo'</table>
</div></fieldset>
';}echo'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$Dc=array("PRIMARY","UNIQUE","INDEX");$S=table_status($a);if(eregi("MyISAM|M?aria",$S["Engine"]))$Dc[]="FULLTEXT";$u=indexes($a);if($w=="sqlite"){unset($Dc[0]);unset($u[""]);}if($_POST&&!$j&&!$_POST["add"]){$sa=array();foreach($_POST["indexes"]as$t){$D=$t["name"];if(in_array($t["type"],$Dc)){$e=array();$bd=array();$O=array();ksort($t["columns"]);foreach($t["columns"]as$x=>$Ta){if($Ta!=""){$ad=$t["lengths"][$x];$O[]=idf_escape($Ta).($ad?"(".(+$ad).")":"");$e[]=$Ta;$bd[]=($ad?$ad:null);}}if($e){$Xb=$u[$D];if($Xb){ksort($Xb["columns"]);ksort($Xb["lengths"]);if($t["type"]==$Xb["type"]&&array_values($Xb["columns"])===$e&&(!$Xb["lengths"]||array_values($Xb["lengths"])===$bd)){unset($u[$D]);continue;}}$sa[]=array($t["type"],$D,"(".implode(", ",$O).")");}}}foreach($u
as$D=>$Xb)$sa[]=array($Xb["type"],$D,"DROP");if(!$sa)redirect(ME."table=".urlencode($a));queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$sa));}page_header('Indexes',$j,array("table"=>$a),$a);$l=array_keys(fields($a));$K=array("indexes"=>$u);if($_POST){$K=$_POST;if($_POST["add"]){foreach($K["indexes"]as$x=>$t){if($t["columns"][count($t["columns"])]!="")$K["indexes"][$x]["columns"][]="";}$t=end($K["indexes"]);if($t["type"]||array_filter($t["columns"],'strlen')||array_filter($t["lengths"],'strlen'))$K["indexes"][]=array("columns"=>array(1=>""));}}else{foreach($K["indexes"]as$x=>$t){$K["indexes"][$x]["name"]=$x;$K["indexes"][$x]["columns"][]="";}$K["indexes"][]=array("columns"=>array(1=>""));}echo'
<form action="" method="post">
<table cellspacing="0" class="nowrap">
<thead><tr><th>Index Type<th>Column (length)<th>Name</thead>
';$v=1;foreach($K["indexes"]as$t){echo"<tr><td>".html_select("indexes[$v][type]",array(-1=>"")+$Dc,$t["type"],($v==count($K["indexes"])?"indexesAddRow(this);":1))."<td>";ksort($t["columns"]);$r=1;foreach($t["columns"]as$x=>$Ta){echo"<span>".html_select("indexes[$v][columns][$r]",array(-1=>"")+$l,$Ta,($r==count($t["columns"])?"indexesAddColumn":"indexesChangeColumn")."(this, '".js_escape($w=="sql"?"":$_GET["indexes"]."_")."');"),"<input type='number' name='indexes[$v][lengths][$r]' class='size' value='".h($t["lengths"][$x])."'> </span>";$r++;}echo"<td><input name='indexes[$v][name]' value='".h($t["name"])."'>\n";$v++;}echo'</table>
<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add next"></noscript>
<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["database"])){if($_POST&&!$j&&!isset($_POST["add_x"])){restart_session();$D=trim($_POST["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$D){if(DB!=""){$_GET["db"]=$D;queries_redirect(preg_replace('~db=[^&]*&~','',ME)."db=".urlencode($D),'Database has been renamed.',rename_database($D,$_POST["collation"]));}else{$h=explode("\n",str_replace("\r","",$D));$jf=true;$Uc="";foreach($h
as$i){if(count($h)==1||$i!=""){if(!create_database($i,$_POST["collation"]))$jf=false;$Uc=$i;}}queries_redirect(ME."db=".urlencode($Uc),'Database has been created.',$jf);}}else{if(!$_POST["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($D).(eregi('^[a-z0-9_]+$',$_POST["collation"])?" COLLATE $_POST[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$j,array(),DB);$d=collations();$D=DB;$Qa=null;if($_POST){$D=$_POST["name"];$Qa=$_POST["collation"];}elseif(DB!="")$Qa=db_collation(DB,$d);elseif($w=="sql"){foreach(get_vals("SHOW GRANTS")as$q){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\\.\\*)?~',$q,$A)&&$A[1]){$D=stripcslashes(idf_unescape("`$A[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($D,"\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($D).'</textarea><br>':'<input id="name" name="name" value="'.h($D).'" maxlength="64">')."\n".($d?html_select("collation",array(""=>"(".'collation'.")")+$d,$Qa):"");?>
<script type='text/javascript'>document.getElementById('name').focus();</script>
<input type="submit" value="Save">
<?php
if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm().">\n";elseif(!$_POST["add_x"]&&$_GET["db"]=="")echo"<input type='image' name='add' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.6.3' alt='+' title='".'Add next'."'>\n";echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["call"])){$da=$_GET["call"];page_header('Call'.": ".h($da),$j);$Ne=routine($da,(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$Cc=array();$Vd=array();foreach($Ne["fields"]as$r=>$k){if(substr($k["inout"],-3)=="OUT")$Vd[$r]="@".idf_escape($k["field"])." AS ".idf_escape($k["field"]);if(!$k["inout"]||substr($k["inout"],0,2)=="IN")$Cc[]=$r;}if(!$j&&$_POST){$Ha=array();foreach($Ne["fields"]as$x=>$k){if(in_array($x,$Cc)){$X=process_input($k);if($X===false)$X="''";if(isset($Vd[$x]))$f->query("SET @".idf_escape($k["field"])." = $X");}$Ha[]=(isset($Vd[$x])?"@".idf_escape($k["field"]):$X);}$H=(isset($_GET["callf"])?"SELECT":"CALL")." ".idf_escape($da)."(".implode(", ",$Ha).")";echo"<p><code class='jush-$w'>".h($H)."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a>\n";if(!$f->multi_query($H))echo"<p class='error'>".error()."\n";else{$g=connect();if(is_object($g))$g->select_db(DB);do{$I=$f->store_result();if(is_object($I))select($I,$g);else
echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$f->affected_rows)."\n";}while($f->next_result());if($Vd)select($f->query("SELECT ".implode(", ",$Vd)));}}echo'
<form action="" method="post">
';if($Cc){echo"<table cellspacing='0'>\n";foreach($Cc
as$x){$k=$Ne["fields"][$x];$D=$k["field"];echo"<tr><th>".$b->fieldName($k);$Y=$_POST["fields"][$D];if($Y!=""){if($k["type"]=="enum")$Y=+$Y;if($k["type"]=="set")$Y=array_sum($Y);}input($k,$Y,(string)$_POST["function"][$D]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];if($_POST&&!$j&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if($_POST["drop"])query_redirect("ALTER TABLE ".table($a)."\nDROP ".($w=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($_GET["name"]),ME."table=".urlencode($a),'Foreign key has been dropped.');else{$bf=array_filter($_POST["source"],'strlen');ksort($bf);$xf=array();foreach($bf
as$x=>$X)$xf[$x]=$_POST["target"][$x];query_redirect("ALTER TABLE ".table($a).($_GET["name"]!=""?"\nDROP ".($w=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($_GET["name"]).",":"")."\nADD FOREIGN KEY (".implode(", ",array_map('idf_escape',$bf)).") REFERENCES ".table($_POST["table"])." (".implode(", ",array_map('idf_escape',$xf)).")".(ereg("^($Gd)\$",$_POST["on_delete"])?" ON DELETE $_POST[on_delete]":"").(ereg("^($Gd)\$",$_POST["on_update"])?" ON UPDATE $_POST[on_update]":""),ME."table=".urlencode($a),($_GET["name"]!=""?'Foreign key has been altered.':'Foreign key has been created.'));$j='Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.'."<br>$j";}}page_header('Foreign key',$j,array("table"=>$a),$a);$K=array("table"=>$a,"source"=>array(""));if($_POST){$K=$_POST;ksort($K["source"]);if($_POST["add"])$K["source"][]="";elseif($_POST["change"]||$_POST["change-js"])$K["target"]=array();}elseif($_GET["name"]!=""){$n=foreign_keys($a);$K=$n[$_GET["name"]];$K["source"][]="";}$bf=array_keys(fields($a));$xf=($a===$K["table"]?$bf:array_keys(fields($K["table"])));$Ae=array();foreach(table_status()as$D=>$S){if(fk_support($S))$Ae[]=$D;}echo'
<form action="" method="post">
<p>
';if($K["db"]==""&&$K["ns"]==""){echo'Target table:
',html_select("table",$Ae,$K["table"],"this.form['change-js'].value = '1'; this.form.submit();"),'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th>Source<th>Target</thead>
';$v=0;foreach($K["source"]as$x=>$X){echo"<tr>","<td>".html_select("source[".(+$x)."]",array(-1=>"")+$bf,$X,($v==count($K["source"])-1?"foreignAddRow(this);":1)),"<td>".html_select("target[".(+$x)."]",$xf,$K["target"][$x]);$v++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$Gd),$K["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$Gd),$K["on_update"]),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';}if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$Ab=false;if($_POST&&!$j){$D=trim($_POST["name"]);$Ab=drop_create("DROP VIEW ".table($a),"CREATE VIEW ".table($D)." AS\n$_POST[select]",($_POST["drop"]?substr(ME,0,-1):ME."table=".urlencode($D)),'View has been dropped.','View has been altered.','View has been created.',$a);}page_header(($a!=""?'Alter view':'Create view'),$j,array("table"=>$a),$a);$K=$_POST;if(!$K&&$a!=""){$K=view($a);$K["name"]=$a;}echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
<p>';textarea("select",$K["select"]);echo'<p>
';if($Ab){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="submit" value="Save">
';if($_GET["view"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$Ic=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$gf=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");if($_POST&&!$j){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($_POST["INTERVAL_FIELD"],$Ic)&&isset($gf[$_POST["STATUS"]])){$Se="\nON SCHEDULE ".($_POST["INTERVAL_VALUE"]?"EVERY ".q($_POST["INTERVAL_VALUE"])." $_POST[INTERVAL_FIELD]".($_POST["STARTS"]?" STARTS ".q($_POST["STARTS"]):"").($_POST["ENDS"]?" ENDS ".q($_POST["ENDS"]):""):"AT ".q($_POST["STARTS"]))." ON COMPLETION".($_POST["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$Se.($aa!=$_POST["EVENT_NAME"]?"\nRENAME TO ".idf_escape($_POST["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($_POST["EVENT_NAME"]).$Se)."\n".$gf[$_POST["STATUS"]]." COMMENT ".q($_POST["EVENT_COMMENT"]).rtrim(" DO\n$_POST[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$j);$K=$_POST;if(!$K&&$aa!=""){$L=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$K=reset($L);}echo'
<form action="" method="post">
<table cellspacing="0">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($K["EVENT_NAME"]),'" maxlength="64">
<tr><th>Start<td><input name="STARTS" value="',h("$K[EXECUTE_AT]$K[STARTS]"),'">
<tr><th>End<td><input name="ENDS" value="',h($K["ENDS"]),'">
<tr><th>Every<td><input type="number" name="INTERVAL_VALUE" value="',h($K["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$Ic,$K["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$gf,$K["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($K["EVENT_COMMENT"]),'" maxlength="64">
<tr><th>&nbsp;<td>',checkbox("ON_COMPLETION","PRESERVE",$K["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$K["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=$_GET["procedure"];$Ne=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$Oe=routine_languages();$Ab=false;if($_POST&&!$j&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){$O=array();$l=(array)$_POST["fields"];ksort($l);foreach($l
as$k){if($k["field"]!="")$O[]=(ereg("^($Fc)\$",$k["inout"])?"$k[inout] ":"").idf_escape($k["field"]).process_type($k,"CHARACTER SET");}$Ab=drop_create("DROP $Ne ".idf_escape($da),"CREATE $Ne ".idf_escape(trim($_POST["name"]))." (".implode(", ",$O).")".(isset($_GET["function"])?" RETURNS".process_type($_POST["returns"],"CHARACTER SET"):"").(in_array($_POST["language"],$Oe)?" LANGUAGE $_POST[language]":"").rtrim("\n$_POST[definition]",";").";",substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$j);$d=get_vals("SHOW CHARACTER SET");sort($d);$K=array("fields"=>array());if($_POST){$K=$_POST;$K["fields"]=(array)$K["fields"];process_fields($K["fields"]);}elseif($da!=""){$K=routine($da,$Ne);$K["name"]=$da;}echo'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
',($Oe?'Language'.": ".html_select("language",$Oe,$K["language"]):""),'<table cellspacing="0" class="nowrap">
';edit_fields($K["fields"],$d,$Ne);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$K["returns"],$d);}echo'</table>
<p>';textarea("definition",$K["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($Ab){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$Mf=trigger_options();$Lf=array("INSERT","UPDATE","DELETE");$Ab=false;if($_POST&&!$j&&in_array($_POST["Timing"],$Mf["Timing"])&&in_array($_POST["Event"],$Lf)&&in_array($_POST["Type"],$Mf["Type"])){$Bf=" $_POST[Timing] $_POST[Event]";$Fd=" ON ".table($a);$Ab=drop_create("DROP TRIGGER ".idf_escape($_GET["name"]).($w=="pgsql"?$Fd:""),"CREATE TRIGGER ".idf_escape($_POST["Trigger"]).($w=="mssql"?$Fd.$Bf:$Bf.$Fd).rtrim(" $_POST[Type]\n$_POST[Statement]",";").";",ME."table=".urlencode($a),'Trigger has been dropped.','Trigger has been altered.','Trigger has been created.',$_GET["name"]);}page_header(($_GET["name"]!=""?'Alter trigger'.": ".h($_GET["name"]):'Create trigger'),$j,array("table"=>$a));$K=$_POST;if(!$K)$K=trigger($_GET["name"])+array("Trigger"=>$a."_bi");echo'
<form action="" method="post" id="form">
<table cellspacing="0">
<tr><th>Time<td>',html_select("Timing",$Mf["Timing"],$K["Timing"],"if (/^".preg_quote($a,"/")."_[ba][iud]$/.test(this.form['Trigger'].value)) this.form['Trigger'].value = '".js_escape($a)."_' + selectValue(this).charAt(0).toLowerCase() + selectValue(this.form['Event']).charAt(0).toLowerCase();"),'<tr><th>Event<td>',html_select("Event",$Lf,$K["Event"],"this.form['Timing'].onchange();"),'<tr><th>Type<td>',html_select("Type",$Mf["Type"],$K["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($K["Trigger"]),'" maxlength="64">
<p>';textarea("Statement",$K["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($Ab){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["user"])){$fa=$_GET["user"];$te=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$K){foreach(explode(",",($K["Privilege"]=="Grant option"?"":$K["Context"]))as$cb)$te[$cb][$K["Privilege"]]=$K["Comment"];}$te["Server Admin"]+=$te["File access on server"];$te["Databases"]["Create routine"]=$te["Procedures"]["Create routine"];unset($te["Procedures"]["Create routine"]);$te["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X)$te["Columns"][$X]=$te["Tables"][$X];unset($te["Server Admin"]["Usage"]);foreach($te["Tables"]as$x=>$X)unset($te["Databases"][$x]);$wd=array();if($_POST){foreach($_POST["objects"]as$x=>$X)$wd[$X]=(array)$wd[$X]+(array)$_POST["grants"][$x];}$rc=array();$Dd="";if(isset($_GET["host"])&&($I=$f->query("SHOW GRANTS FOR ".q($fa)."@".q($_GET["host"])))){while($K=$I->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$K[0],$A)&&preg_match_all('~ *([^(,]*[^ ,(])( *\\([^)]+\\))?~',$A[1],$gd,PREG_SET_ORDER)){foreach($gd
as$X){if($X[1]!="USAGE")$rc["$A[2]$X[2]"][$X[1]]=true;if(ereg(' WITH GRANT OPTION',$K[0]))$rc["$A[2]$X[2]"]["GRANT OPTION"]=true;}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$K[0],$A))$Dd=$A[1];}}if($_POST&&!$j){$Ed=(isset($_GET["host"])?q($fa)."@".q($_GET["host"]):"''");$xd=q($_POST["user"])."@".q($_POST["host"]);$fe=q($_POST["pass"]);if($_POST["drop"])query_redirect("DROP USER $Ed",ME."privileges=",'User has been dropped.');else{$hb=false;if($Ed!=$xd){$hb=queries(($f->server_info<5?"GRANT USAGE ON *.* TO":"CREATE USER")." $xd IDENTIFIED BY".($_POST["hashed"]?" PASSWORD":"")." $fe");$j=!$hb;}elseif($_POST["pass"]!=$Dd||!$_POST["hashed"])queries("SET PASSWORD FOR $xd = ".($_POST["hashed"]?$fe:"PASSWORD($fe)"));if(!$j){$Ke=array();foreach($wd
as$_d=>$q){if(isset($_GET["grant"]))$q=array_filter($q);$q=array_keys($q);if(isset($_GET["grant"]))$Ke=array_diff(array_keys(array_filter($wd[$_d],'strlen')),$q);elseif($Ed==$xd){$Cd=array_keys((array)$rc[$_d]);$Ke=array_diff($Cd,$q);$q=array_diff($q,$Cd);unset($rc[$_d]);}if(preg_match('~^(.+)\\s*(\\(.*\\))?$~U',$_d,$A)&&(!grant("REVOKE",$Ke,$A[2]," ON $A[1] FROM $xd")||!grant("GRANT",$q,$A[2]," ON $A[1] TO $xd"))){$j=true;break;}}}if(!$j&&isset($_GET["host"])){if($Ed!=$xd)queries("DROP USER $Ed");elseif(!isset($_GET["grant"])){foreach($rc
as$_d=>$Ke){if(preg_match('~^(.+)(\\(.*\\))?$~U',$_d,$A))grant("REVOKE",array_keys($Ke),$A[2]," ON $A[1] FROM $xd");}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$j);if($hb)$f->query("DROP USER $xd");}}page_header((isset($_GET["host"])?'Username'.": ".h("$fa@$_GET[host]"):'Create user'),$j,array("privileges"=>array('','Privileges')));if($_POST){$K=$_POST;$rc=$wd;}else{$K=$_GET+array("host"=>$f->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$K["pass"]=$Dd;if($Dd!="")$K["hashed"]=true;$rc[(DB==""||$rc?"":idf_escape(addcslashes(DB,"%_"))).".*"]=array();}echo'<form action="" method="post">
<table cellspacing="0">
<tr><th>Server<td><input name="host" maxlength="60" value="',h($K["host"]),'">
<tr><th>Username<td><input name="user" maxlength="16" value="',h($K["user"]),'">
<tr><th>Password<td><input id="pass" name="pass" value="',h($K["pass"]),'">
';if(!$K["hashed"]){echo'<script type="text/javascript">typePassword(document.getElementById(\'pass\'));</script>';}echo
checkbox("hashed",1,$K["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);"),'</table>

';echo"<table cellspacing='0'>\n","<thead><tr><th colspan='2'><a href='http://dev.mysql.com/doc/refman/".substr($f->server_info,0,3)."/en/grant.html#priv_level' target='_blank' rel='noreferrer'>".'Privileges'."</a>";$r=0;foreach($rc
as$_d=>$q){echo'<th>'.($_d!="*.*"?"<input name='objects[$r]' value='".h($_d)."' size='10'>":"<input type='hidden' name='objects[$r]' value='*.*' size='10'>*.*");$r++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$cb=>$sb){foreach((array)$te[$cb]as$se=>$Wa){echo"<tr".odd()."><td".($sb?">$sb<td":" colspan='2'").' lang="en" title="'.h($Wa).'">'.h($se);$r=0;foreach($rc
as$_d=>$q){$D="'grants[$r][".h(strtoupper($se))."]'";$Y=$q[strtoupper($se)];if($cb=="Server Admin"&&$_d!=(isset($rc["*.*"])?"*.*":".*"))echo"<td>&nbsp;";elseif(isset($_GET["grant"]))echo"<td><select name=$D><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";else
echo"<td align='center'><input type='checkbox' name=$D value='1'".($Y?" checked":"").($se=="All privileges"?" id='grants-$r-all'":($se=="Grant option"?"":" onclick=\"if (this.checked) formUncheck('grants-$r-all');\"")).">";$r++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")&&$_POST&&!$j){$Rc=0;foreach((array)$_POST["kill"]as$X){if(queries("KILL ".(+$X)))$Rc++;}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$Rc),$Rc||!$_POST["kill"]);}page_header('Process list',$j);echo'
<form action="" method="post">
<table cellspacing="0" onclick="tableClick(event);" ondblclick="tableClick(event, true);" class="nowrap checkable">
';$r=-1;foreach(process_list()as$r=>$K){if(!$r)echo"<thead><tr lang='en'>".(support("kill")?"<th>&nbsp;":"")."<th>".implode("<th>",array_keys($K))."</thead>\n";echo"<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]",$K["Id"],0):"");foreach($K
as$x=>$X)echo"<td>".(($w=="sql"&&$x=="Info"&&ereg("Query|Killed",$K["Command"])&&$X!="")||($w=="pgsql"&&$x=="current_query"&&$X!="<IDLE>")||($w=="oracle"&&$x=="sql_text"&&$X!="")?"<code class='jush-$w'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($K["db"]!=""?"db=".urlencode($K["db"])."&":"")."sql=".urlencode($X)).'">'.'Edit'.'</a>':nbsp($X));echo"\n";}echo'</table>
<script type=\'text/javascript\'>tableCheck();</script>
<p>
';if(support("kill")){echo($r+1)."/".sprintf('%d in total',$f->result("SELECT @@max_connections")),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$T,'">
</form>
';}elseif(isset($_GET["select"])){$a=$_GET["select"];$S=table_status($a);$u=indexes($a);$l=fields($a);$n=column_foreign_keys($a);$Bd="";if($S["Oid"]=="t"){$Bd=($w=="sqlite"?"rowid":"oid");$u[]=array("type"=>"PRIMARY","columns"=>array($Bd));}parse_str($_COOKIE["adminer_import"],$la);$Le=array();$e=array();$_f=null;foreach($l
as$x=>$k){$D=$b->fieldName($k);if(isset($k["privileges"]["select"])&&$D!=""){$e[$x]=html_entity_decode(strip_tags($D));if(is_shortable($k))$_f=$b->selectLengthProcess();}$Le+=$k["privileges"];}list($M,$sc)=$b->selectColumnsProcess($e,$u);$Jc=count($sc)<count($M);$Z=$b->selectSearchProcess($l,$u);$Nd=$b->selectOrderProcess($l,$u);$y=$b->selectLimitProcess();$pc=($M?implode(", ",$M):"*".($Bd?", $Bd":""));if($w=="sql"){foreach($e
as$x=>$X){$ta=convert_field($l[$x]);if($ta)$pc.=", $ta AS ".idf_escape($x);}}$pc.="\nFROM ".table($a);$tc=($sc&&$Jc?"\nGROUP BY ".implode(", ",$sc):"").($Nd?"\nORDER BY ".implode(", ",$Nd):"");if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Uf=>$K){$ta=convert_field($l[key($K)]);echo$f->result("SELECT".limit(($ta?$ta:idf_escape(key($K)))." FROM ".table($a)," WHERE ".where_check($Uf,$l).($Z?" AND ".implode(" AND ",$Z):"").($Nd?" ORDER BY ".implode(", ",$Nd):""),1));}exit;}if($_POST&&!$j){$kg="(".implode(") OR (",array_map('where_check',(array)$_POST["check"])).")";$pe=$Wf=null;foreach($u
as$t){if($t["type"]=="PRIMARY"){$pe=array_flip($t["columns"]);$Wf=($M?$pe:array());break;}}foreach((array)$Wf
as$x=>$X){if(in_array(idf_escape($x),$M))unset($Wf[$x]);}if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");if(!is_array($_POST["check"])||$Wf===array()){$jg=$Z;if(is_array($_POST["check"]))$jg[]="($kg)";$H="SELECT $pc".($jg?"\nWHERE ".implode(" AND ",$jg):"").$tc;}else{$Sf=array();foreach($_POST["check"]as$X)$Sf[]="(SELECT".limit($pc,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$l).$tc,1).")";$H=implode(" UNION ALL ",$Sf);}$b->dumpData($a,"table",$H);exit;}if(!$b->selectEmailProcess($Z,$n)){if($_POST["save"]||$_POST["delete"]){$I=true;$ma=0;$H=table($a);$O=array();if(!$_POST["delete"]){foreach($e
as$D=>$X){$X=process_input($l[$D]);if($X!==null){if($_POST["clone"])$O[idf_escape($D)]=($X!==false?$X:idf_escape($D));elseif($X!==false)$O[]=idf_escape($D)." = $X";}}$H.=($_POST["clone"]?" (".implode(", ",array_keys($O)).")\nSELECT ".implode(", ",$O)."\nFROM ".table($a):" SET\n".implode(",\n",$O));}if($_POST["delete"]||$O){$Ua="UPDATE";if($_POST["delete"]){$Ua="DELETE";$H="FROM $H";}if($_POST["clone"]){$Ua="INSERT";$H="INTO $H";}if($_POST["all"]||($Wf===array()&&$_POST["check"])||$Jc){$I=queries("$Ua $H".($_POST["all"]?($Z?"\nWHERE ".implode(" AND ",$Z):""):"\nWHERE $kg"));$ma=$f->affected_rows;}else{foreach((array)$_POST["check"]as$X){$I=queries($Ua.limit1($H,"\nWHERE ".where_check($X,$l)));if(!$I)break;$ma+=$f->affected_rows;}}}$B=lang(array('%d item has been affected.','%d items have been affected.'),$ma);if($_POST["clone"]&&$I&&$ma==1){$Vc=last_id();if($Vc)$B=sprintf('Item%s has been inserted.'," $Vc");}queries_redirect(remove_from_uri("page"),$B,$I);}elseif(!$_POST["import"]){if(!$_POST["val"])$j='Ctrl+click on a value to modify it.';else{$I=true;$ma=0;foreach($_POST["val"]as$Uf=>$K){$O=array();foreach($K
as$x=>$X){$x=bracket_escape($x,1);$O[]=idf_escape($x)." = ".(ereg('char|text',$l[$x]["type"])||$X!=""?$b->processInput($l[$x],$X):"NULL");}$H=table($a)." SET ".implode(", ",$O);$jg=" WHERE ".where_check($Uf,$l).($Z?" AND ".implode(" AND ",$Z):"");$I=queries("UPDATE".($Jc?" $H$jg":limit1($H,$jg)));if(!$I)break;$ma+=$f->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ma),$I);}}elseif(is_string($ec=get_file("csv_file",true))){cookie("adminer_import","output=".urlencode($la["output"])."&format=".urlencode($_POST["separator"]));$I=true;$Sa=array_keys($l);preg_match_all('~(?>"[^"]*"|[^"\\r\\n]+)+~',$ec,$gd);$ma=count($gd[0]);begin();$Xe=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));foreach($gd[0]as$x=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$Xe]*)$Xe~",$X.$Xe,$hd);if(!$x&&!array_diff($hd[1],$Sa)){$Sa=$hd[1];$ma--;}else{$O=array();foreach($hd[1]as$r=>$Pa)$O[idf_escape($Sa[$r])]=($Pa==""&&$l[$Sa[$r]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$Pa))));$I=insert_update($a,$O,$pe);if(!$I)break;}}if($I)queries("COMMIT");queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ma),$I);queries("ROLLBACK");}else$j=upload_error($ec);}}$qf=$b->tableName($S);if(is_ajax())ob_start();page_header('Select'.": $qf",$j);$O=null;if(isset($Le["insert"])){$O="";foreach((array)$_GET["where"]as$X){if(count($n[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!ereg('[_%]',$X["val"]))))$O.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}$b->selectLinks($S,$O);if(!$e)echo"<p class='error'>".'Unable to select the table'.($l?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($M,$e);$b->selectSearchPrint($Z,$e,$u);$b->selectOrderPrint($Nd,$e,$u);$b->selectLimitPrint($y);$b->selectLengthPrint($_f);$b->selectActionPrint($u);echo"</form>\n";$E=$_GET["page"];if($E=="last"){$nc=$f->result("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):""));$E=floor(max(0,$nc-1)/$y);}$H=$b->selectQueryBuild($M,$Z,$sc,$Nd,$y,$E);if(!$H)$H="SELECT".limit((+$y&&$sc&&$Jc&&$w=="sql"?"SQL_CALC_FOUND_ROWS ":"").$pc,($Z?"\nWHERE ".implode(" AND ",$Z):"").$tc,($y!=""?+$y:null),($E?$y*$E:0),"\n");echo$b->selectQuery($H);$I=$f->query($H);if(!$I)echo"<p class='error'>".error()."\n";else{if($w=="mssql")$I->seek($y*$E);$Jb=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$L=array();while($K=$I->fetch_assoc()){if($E&&$w=="oracle")unset($K["RNUM"]);$L[]=$K;}if($_GET["page"]!="last")$nc=(+$y&&$sc&&$Jc?($w=="sql"?$f->result(" SELECT FOUND_ROWS()"):$f->result("SELECT COUNT(*) FROM ($H) x")):count($L));if(!$L)echo"<p class='message'>".'No rows.'."\n";else{$_a=$b->backwardKeys($a,$qf);echo"<table id='table' cellspacing='0' class='nowrap checkable' onclick='tableClick(event);' ondblclick='tableClick(event, true);' onkeydown='return editingKeydown(event);'>\n","<thead><tr>".(!$sc&&$M?"":"<td><input type='checkbox' id='all-page' onclick='formCheck(this, /check/);'> <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'edit'."</a>");$vd=array();$p=array();reset($M);$ye=1;foreach($L[0]as$x=>$X){if($x!=$Bd){$X=$_GET["columns"][key($M)];$k=$l[$M?($X?$X["col"]:current($M)):$x];$D=($k?$b->fieldName($k,$ye):"*");if($D!=""){$ye++;$vd[$x]=$D;$Ta=idf_escape($x);$zc=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($x);$sb="&desc%5B0%5D=1";echo'<th onmouseover="columnMouse(this);" onmouseout="columnMouse(this, \' hidden\');">','<a href="'.h($zc.($Nd[0]==$Ta||$Nd[0]==$x||(!$Nd&&$Jc&&$sc[0]==$Ta)?$sb:'')).'">';echo(!$M||$X?apply_sql_function($X["fun"],$D):h(current($M)))."</a>";echo"<span class='column hidden'>","<a href='".h($zc.$sb)."' title='".'descending'."' class='text'> ‚Üì</a>";if(!$X["fun"])echo'<a href="#fieldset-search" onclick="selectSearch(\''.h(js_escape($x)).'\'); return false;" title="'.'Search'.'" class="text jsonly"> =</a>';echo"</span>";}$p[$x]=$X["fun"];next($M);}}$bd=array();if($_GET["modify"]){foreach($L
as$K){foreach($K
as$x=>$X)$bd[$x]=max($bd[$x],min(40,strlen(utf8_decode($X))));}}echo($_a?"<th>".'Relations':"")."</thead>\n";if(is_ajax()){if($y%2==1&&$E%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($L,$n)as$C=>$K){$Tf=unique_array($L[$C],$u);$Uf="";foreach($Tf
as$x=>$X)$Uf.="&".($X!==null?urlencode("where[".bracket_escape($x)."]")."=".urlencode($X):"null%5B%5D=".urlencode($x));echo"<tr".odd().">".(!$sc&&$M?"":"<td>".checkbox("check[]",substr($Uf,1),in_array(substr($Uf,1),(array)$_POST["check"]),"","this.form['all'].checked = false; formUncheck('all-page');").($Jc||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Uf)."'>".'edit'."</a>"));foreach($K
as$x=>$X){if(isset($vd[$x])){$k=$l[$x];if($X!=""&&(!isset($Jb[$x])||$Jb[$x]!=""))$Jb[$x]=(is_mail($X)?$vd[$x]:"");$z="";$X=$b->editVal($X,$k);if($X!==null){if(ereg('blob|bytea|raw|file',$k["type"])&&$X!="")$z=ME.'download='.urlencode($a).'&field='.urlencode($x).$Uf;if($X==="")$X="&nbsp;";elseif($_f!=""&&is_shortable($k))$X=shorten_utf8($X,max(0,+$_f));else$X=h($X);if(!$z){foreach((array)$n[$x]as$m){if(count($n[$x])==1||end($m["source"])==$x){$z="";foreach($m["source"]as$r=>$bf)$z.=where_link($r,$m["target"][$r],$L[$C][$bf]);$z=($m["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\\1'.urlencode($m["db"]),ME):ME).'select='.urlencode($m["table"]).$z;if(count($m["source"])==1)break;}}}if($x=="COUNT(*)"){$z=ME."select=".urlencode($a);$r=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Tf))$z.=where_link($r++,$W["col"],$W["val"],$W["op"]);}foreach($Tf
as$Oc=>$W)$z.=where_link($r++,$Oc,$W);}}if(!$z&&($z=$b->selectLink($K[$x],$k))===null){if(is_mail($K[$x]))$z="mailto:$K[$x]";if($we=is_url($K[$x]))$z=($we=="http"&&$ba?$K[$x]:"$we://www.adminer.org/redirect/?url=".urlencode($K[$x]));}$s=h("val[$Uf][".bracket_escape($x)."]");$Y=$_POST["val"][$Uf][bracket_escape($x)];$vc=h($Y!==null?$Y:$K[$x]);$fd=strpos($X,"<i>...</i>");$Fb=is_utf8($X)&&$L[$C][$x]==$K[$x]&&!$p[$x];$zf=ereg('text|lob',$k["type"]);echo(($_GET["modify"]&&$Fb)||$Y!==null?"<td>".($zf?"<textarea name='$s' cols='30' rows='".(substr_count($K[$x],"\n")+1)."'>$vc</textarea>":"<input name='$s' value='$vc' size='$bd[$x]'>"):"<td id='$s' onclick=\"selectClick(this, event, ".($fd?2:($zf?1:0)).($Fb?"":", '".h('Use edit link to modify this value.')."'").");\">".$b->selectVal($X,$z,$k));}}if($_a)echo"<td>";$b->backwardKeysPrint($_a,$L[$C]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n",(!$sc&&$M?"":"<script type='text/javascript'>tableCheck();</script>\n");}if(($L||$E)&&!is_ajax()){$Tb=true;if($_GET["page"]!="last"&&+$y&&!$Jc&&($nc>=$y||$E)){$nc=found_rows($S,$Z);if($nc<max(1e4,2*($E+1)*$y))$nc=reset(slow_query("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):"")));else$Tb=false;}echo"<p class='pages'>";if(+$y&&($nc===false||$nc>$y)){$jd=($nc===false?$E+(count($L)>=$y?2:1):floor(($nc-1)/$y));echo'<a href="'.h(remove_from_uri("page"))."\" onclick=\"pageClick(this.href, +prompt('".'Page'."', '".($E+1)."'), event); return false;\">".'Page'."</a>:",pagination(0,$E).($E>5?" ...":"");for($r=max(1,$E-4);$r<min($jd,$E+5);$r++)echo
pagination($r,$E);echo($E+5<$jd?" ...":"").($Tb&&$nc!==false?pagination($jd,$E):' <a href="'.h(remove_from_uri("page")."&page=last").'">'.'last'."</a>");}echo($nc!==false?" (".($Tb?"":"~ ").lang(array('%d row','%d rows'),$nc).")":""),(+$y&&($nc===false?count($L)+1:$nc-$E*$y)>$y?' <a href="'.h(remove_from_uri("page")."&page=".($E+1)).'" onclick="return !selectLoadMore(this, '.(+$y).', \''.'Loading'.'\');">'.'Load more data'.'</a>':'')," ".checkbox("all",1,0,'whole result')."\n";if($b->selectCommandPrint()){echo'<fieldset><legend>Edit</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Ctrl+click on a value to modify it.'.'" class="jsonly"');?>>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure? (' + (this.form['all'].checked ? <?php echo$nc,' : formChecked(this, /check/)) + \')\');">
</div></fieldset>
';}$lc=$b->dumpFormat();if($lc){print_fieldset("export",'Export');$Wd=$b->dumpOutput();echo($Wd?html_select("output",$Wd,$la["output"])." ":""),html_select("format",$lc,$la["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}}if($b->selectImportPrint()){print_fieldset("import",'Import',!$L);echo"<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$la["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($Jb,'strlen'),$e);echo"<p><input type='hidden' name='token' value='$T'></p>\n","</form>\n";}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$ff=isset($_GET["status"]);page_header($ff?'Status':'Variables');$eg=($ff?show_status():show_variables());if(!$eg)echo"<p class='message'>".'No rows.'."\n";else{echo"<table cellspacing='0'>\n";foreach($eg
as$x=>$X){echo"<tr>","<th><code class='jush-".$w.($ff?"status":"set")."'>".h($x)."</code>","<td>".nbsp($X);}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$nf=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$S){$s=js_escape($S["Name"]);json_row("Comment-$s",nbsp($S["Comment"]));if(!is_view($S)){foreach(array("Engine","Collation")as$x)json_row("$x-$s",nbsp($S[$x]));foreach($nf+array("Auto_increment"=>0,"Rows"=>0)as$x=>$X){if($S[$x]!=""){$X=number_format($S[$x],0,'.',',');json_row("$x-$s",($x=="Rows"&&$X&&$S["Engine"]==($df=="pgsql"?"table":"InnoDB")?"~ $X":$X));if(isset($nf[$x]))$nf[$x]+=($S["Engine"]!="InnoDB"||$x!="Data_free"?$S[$x]:0);}elseif(array_key_exists($x,$S))json_row("$x-$s");}}}foreach($nf
as$x=>$X)json_row("sum-$x",number_format($X,0,'.',','));json_row("");}elseif($_GET["script"]=="kill")$f->query("KILL ".(+$_POST["kill"]));else{foreach(count_tables($b->databases())as$i=>$X)json_row("tables-".js_escape($i),$X);json_row("");}exit;}else{$wf=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($wf&&!$j&&!$_POST["search"]){$I=true;$B="";if($w=="sql"&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$I=truncate_tables($_POST["tables"]);$B='Tables have been truncated.';}elseif($_POST["move"]){$I=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$B='Tables have been moved.';}elseif($_POST["copy"]){$I=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$B='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$I=drop_views($_POST["views"]);if($I&&$_POST["tables"])$I=drop_tables($_POST["tables"]);$B='Tables have been dropped.';}elseif($w!="sql"){$I=($w=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"),$_POST["tables"]));$B='Tables have been optimized.';}elseif($_POST["tables"]&&($I=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('idf_escape',$_POST["tables"]))))){while($K=$I->fetch_assoc())$B.="<b>".h($K["Table"])."</b>: ".h($K["Msg_text"])."<br>";}queries_redirect(substr(ME,0,-1),$B,$I);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$j,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3>".'Tables and views'."</h3>\n";$vf=tables_list();if(!$vf)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n","<p>".'Search data in tables'.": <input type='search' name='query' value='".h($_POST["query"])."'> <input type='submit' name='search' value='".'Search'."'>\n";if($_POST["search"]&&$_POST["query"]!="")search_tables();echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);' ondblclick='tableClick(event, true);'>\n",'<thead><tr class="wrap"><td><input id="check-all" type="checkbox" onclick="formCheck(this, /^(tables|views)\[/);">','<th>'.'Table','<td>'.'Engine','<td>'.'Collation','<td>'.'Data Length','<td>'.'Index Length','<td>'.'Data Free','<td>'.'Auto Increment','<td>'.'Rows',(support("comment")?'<td>'.'Comment':''),"</thead>\n";foreach($vf
as$D=>$U){$fg=($U!==null&&!eregi("table",$U));echo'<tr'.odd().'><td>'.checkbox(($fg?"views[]":"tables[]"),$D,in_array($D,$wf,true),"","formUncheck('check-all');"),'<th><a href="'.h(ME).'table='.urlencode($D).'" title="'.'Show structure'.'">'.h($D).'</a>';if($fg){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($D).'" title="'.'Alter view'.'">'.'View'.'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($D).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$x=>$z)echo($z?"<td align='right'><a href='".h(ME."$z[0]=").urlencode($D)."' id='$x-".h($D)."' title='$z[1]'>?</a>":"<td id='$x-".h($D)."'>&nbsp;");}echo(support("comment")?"<td id='Comment-".h($D)."'>&nbsp;":"");}echo"<tr><td>&nbsp;<th>".sprintf('%d in total',count($vf)),"<td>".nbsp($w=="sql"?$f->result("SELECT @@storage_engine"):""),"<td>".nbsp(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$x)echo"<td align='right' id='sum-$x'>&nbsp;";echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n";if(!information_schema(DB)){echo"<p>".(ereg('^(sql|sqlite|pgsql)$',$w)?($w!="sqlite"?"<input type='submit' value='".'Analyze'."'> ":"")."<input type='submit' name='optimize' value='".'Optimize'."'> ":"").($w=="sql"?"<input type='submit' name='check' value='".'Check'."'> <input type='submit' name='repair' value='".'Repair'."'> ":"")."<input type='submit' name='truncate' value='".'Truncate'."'".confirm("formChecked(this, /tables/)")."> <input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /tables|views/)").">\n";$h=(support("scheme")?schemas():$b->databases());if(count($h)!=1&&$w!="sqlite"){$i=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($h?html_select("target",$h,$i):'<input name="target" value="'.h($i).'">')," <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'>":""),"\n";}echo"<input type='hidden' name='token' value='$T'>\n";}echo"</form>\n";}echo'<p><a href="'.h(ME).'create=">'.'Create table'."</a>\n";if(support("view"))echo'<a href="'.h(ME).'view=">'.'Create view'."</a>\n";if(support("routine")){echo"<h3>".'Routines'."</h3>\n";$Pe=routines();if($Pe){echo"<table cellspacing='0'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td>&nbsp;</thead>\n";odd('');foreach($Pe
as$K){echo'<tr'.odd().'>','<th><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($K["ROUTINE_NAME"]).'">'.h($K["ROUTINE_NAME"]).'</a>','<td>'.h($K["ROUTINE_TYPE"]),'<td>'.h($K["DTD_IDENTIFIER"]),'<td><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($K["ROUTINE_NAME"]).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p>'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a> ':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("event")){echo"<h3>".'Events'."</h3>\n";$L=get_rows("SHOW EVENTS");if($L){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."</thead>\n";foreach($L
as$K){echo"<tr>",'<th><a href="'.h(ME).'event='.urlencode($K["Name"]).'">'.h($K["Name"])."</a>","<td>".($K["Execute at"]?'At given time'."<td>".$K["Execute at"]:'Every'." ".$K["Interval value"]." ".$K["Interval field"]."<td>$K[Starts]"),"<td>$K[Ends]";}echo"</table>\n";$Sb=$f->result("SELECT @@event_scheduler");if($Sb&&$Sb!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Sb)."\n";}echo'<p><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($vf)echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=db');</script>\n";}}}page_footer();