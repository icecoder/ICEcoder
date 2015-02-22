<?php
// Traditional Chinese language translation
// by: @lzh370 (GitHub)
//     lzh370@qq.com

// Please preserve formatting, line breaks, special characters, anything in <tags> and HTML equivalents (eg &amp;). Translations on right side.

$text = array(

// / [ROOT LEVEL]

	"editor" =>
	array(
		"Click icons for..."		=> "<b>點擊圖標<br>求助 &amp;<br>實用信息</b>",
		"server"			=> "伺服器",
		"Server name, OS..."		=> "伺服器名稱, OS & IP:",
		"Root"				=> "根目錄:",
		"ICEcoder root"			=> "ICEcoder 根目錄:",
		"PHP version"			=> "PHP 版本:",
		"Date & time"			=> "日期 & 時間:",
		"your device"			=> "你的設備",
		"Browser"			=> "瀏覽器:",
		"Your IP"			=> "你的 IP:",
		"files"				=> "文件",
		"Last 10 files..."		=> "最後10個打開的文件:",
		"none"				=> "[none]",
		"test suite"			=> "測試套件",
		"Run unit tests"		=> "運行單元測試",
		"dev mode"			=> "開發模式",
		"Status"			=> "狀態",
		"Using"				=> "使用",
		"You can switch..."		=> "你可以在 lib/config__settings.php 中設置開發模式開關",
		"results"			=> "結果"

	),

	"files" =>
	array(
		"Lock"				=> "鎖定",
		"Refresh"			=> "刷新",
		"ROOT"				=> "[ROOT]"

	),

	"index" =>
	array(
		"UPDATE INFO"			=> "更新信息",
		"now available"			=> "現在可以",
		"Your version is"		=> "你的版本是",
		"Update now"			=> "現在更新",
		"You have some..."		=> "你有一些未保存的更改",
		"Are you sure you want to close?" => "Are you sure you want to close?",
		"working"			=> "裝載中",
		"Color picker"			=> "顔色選擇器",
		"New File"			=> "新建文件",
		"New Folder"			=> "新建文件夾",
		"Upload File(s)"		=> "上傳文件",
		"Paste"				=> "粘貼",
		"Open"				=> "打開",
		"Copy"				=> "複製",
		"Duplicate"			=> "副本",
		"Delete"			=> "刪除",
		"Rename"			=> "重命名",
		"View Webpage"			=> "預覽網頁",
		"Download"			=> "下載",
		"Properties"			=> "屬性",
		"File"				=> "文件",
		"Edit"				=> "編輯",
		"Remote"			=> "遠程",
		"Help"				=> "幫助",
		"Save"				=> "保存",
		"Save As"			=> "另存爲",
		"Live Preview"			=> "實時預覽",
		"Upload"			=> "上傳",
		"Zip"				=> "壓縮",
		"Print"				=> "打印",
		"Fullscreen toggle"		=> "全屏切換",
		"Logout"			=> "登出",
		"Undo"				=> "撤銷",
		"Redo"				=> "重做",
		"Indent more"			=> "增加縮進",
		"Indent less"			=> "减少縮進",
		"Autocomplete"			=> "自動完成",
		"Comment/Uncomment"		=> "注釋 / 清除注釋",
		"Jump to Definition"		=> "跳轉指定行",
		"Manual"			=> "手冊",
		"Shortcuts"			=> "快捷鍵",
		"Settings"			=> "選項",
		"Search for selected"		=> "搜索選項",
		"website"			=> "網站",
		"Close all tabs"		=> "關閉所有選項卡",
		"Alphabetize tabs"		=> "按字母順序排序選項卡",
//		"Find"				=> "檢索",
//		"in"				=> "in",
//		"and"				=> "and",
//		"replace"			=> "替換",
//		"replace all"			=> "替換所有",
//		"this document"			=> "當前文件",
//		"open documents"		=> "打開的文件",
//		"all files"			=> "所有文件",
//		"all filenames"			=> "所有文件名",
		"Turn on/off..."		=> "開啓/關閉代碼輔助",
		"Code Assist"			=> "代碼輔助",
		"Go to Line"			=> "轉到行",
		"View"				=> "預覽",
		"Bug reporting not active"	=> "錯誤報告沒有激活"
	),

// /LIB

	"bug-files-check" =>
	array(
		"Found in"			=> "檢索到:"
	),

	"file-control" =>
	array(
		"Sorry"				=> "抱歉",
		"does not seem..."		=> "服務器上不存在",
		"Sorry, could not..."		=> "抱歉, 不能獲取内容",
		"Sorry, cannot create..."	=> "抱歉, 不能創建文件夾",
		"Sorry, cannot copy"		=> "抱歉, 不能複製",
		"into"				=> "到",
		"Uploaded file(s) OK"		=> "上傳文件完成",
		"Sorry, cannot upload"		=> "抱歉, 不能上傳",
		"Sorry, cannot upload..."	=> "抱歉, 演示模式不允許上傳文件",
		"Sorry, cannot rename"		=> "抱歉, 不能重命名",
		"Maybe public write..."		=> "也許父文件夾也需要寫入權限?",
		"Sorry, cannot move"		=> "抱歉, 不能移動",
		"Sorry, cannot save"		=> "抱歉, 不能保存",
		"Sorry, cannot replace..."	=> "抱歉, 目標不能替換文本",
		"Sorry, cannot change..."	=> "抱歉, 目標不能更改權限",
		"Sorry, cannot delete..."	=> "抱歉, 不能刪除根級別ROOT",
		"Sorry, cannot delete"		=> "抱歉, 不能刪除",
		"Sorry, this file..."		=> "抱歉, 此文件已更改, 不能保存",
		"Reload this file..."		=> "重新加載該文件, 你的版本複製到一個新文件?",
		"There was a..."		=> "這些代碼還沒有準備好. 因此 ICEcoder 需要重新加載.",
		"displayed at"			=> "顯示在",
		"Enter filename to..."		=> "輸入文件名并保存到",
		"That file exists..."		=> "該文件已存在, 要覆蓋嗎?",
		"Saving"			=> "正在保存"
	),

	"get-branch" =>
	array(
		"There are no..."		=> "本地文件與 GitHub repo 沒有明顯差異. 切換回普通模式?",
		"Sorry, there was..."		=> "抱歉, 這裏有一個錯誤, 代碼:",
		"Your local folder..."		=> "你的本地文件夾是空的, 你是否想克隆"
	),

	"github-manager" =>
	array(
		"Sorry, cannot create..."	=> "抱歉, 不能創建目錄到",
		"Cannot update config..."	=> "不能更新配置文件. 請對",
		"and try again"			=> "增加寫入權限, 并再次嘗試",
		"saving github paths"		=> "正在保存 github 路徑...",
		"github paths"			=> "github 路徑",
		"Choose existing path"		=> "選擇現有路徑",
		"Local path"			=> "本地路徑",
		"Remote GitHub path"		=> "遠程 GitHub 路徑",
		"Choose"			=> "選擇",
		"Set local and..."		=> "設置本地路徑和遠程路徑需要去除空格",
		"Update"			=> "更新",
		"Add new path"			=> "增加新路徑",
		"Add"				=> "增加",
		"Usage Info"			=> "使用方法",
		"Enter relative local..."	=> "輸入本地相對路徑 (/server/myfiles) 和 GitHub 絕對路徑 (https://github.com/user/repo 或 https://github.com/user/repo/tree/branch 各分隻), 按照示例. 完成后你就建立了這兩個源路徑, 它應該成對數存在.",
		"You can then..."		=> "你可以選擇一個路徑作爲 ICEcoder 的根路徑.",
		"The file manager..."		=> "文件管理器右側會顯示一個新的GitHub圖標, 您可以點擊執行, 會自動校驗并顯示本地與github源之間的差異. 這些差異可以提交和推送到GitHub上的遠程路徑或克隆到您的本地路徑, 用來同步您的文檔.",
		"If you want..."		=> "如果您想設置一個根路徑, 可以在 幫助 > 設置窗口 中進行設置."
	),

	"github" =>
	array(
		"Sorry, you do..."		=> "抱歉, 您的服務器沒有啓用 OpenSSL 的 PHP 實例, 因此 https 目前不可用. GitHub 的數據傳輸必須要用 https 連接, 請修改 php.ini 設置, 重啓您的服務器并重新嘗試"
	),

	"headers" =>
	array(
		"Bad CSRF token..."		=> "錯誤的 CSRF token. 請在 https://github.com/mattpass/ICEcoder 報告錯誤信息, 以便我們修復它."
	),

	"help" =>
	array(
		"shortcuts"			=> "快捷鍵",
		"Within document"		=> "文檔",
		"On Tabs"			=> "選項卡",
		"Within file manager"		=> "文件管理器",
		"Anywhere"			=> "其他",
		"Space"				=> "空格",
		"Click"				=> "單擊",
		"or"				=> "或",
		"Left click"			=> "左鍵單擊",
		"Middle click"			=> "中鍵單擊",
		"Double click tap..."		=> "雙擊 / 手指點擊 (移動端)",
		"Right click"			=> "右鍵單擊",
		"Middle scrollwheel"		=> "中間滾輪滾動",
		"Drag"				=> "拖動",
		"Autocomplete add snippet"	=> "自動完成 / 添加片段",
		"Multiple select"		=> "多選",
		"Move line up"			=> "向上移動行",
		"Move line down"		=> "向下移動行",
		"Duplicate lines"		=> "復制行",
		"Remove lines"			=> "刪除行",
		"Insert line before"		=> "前插入行",
		"Insert line after"		=> "后插入行",
		"Search for selected"		=> "搜索選擇",
		"Jump to definition"		=> "跳轉到 / 跳轉回",
		"Comment uncomment"		=> "注釋 / 清楚注釋",
		"Insert tab indent"		=> "插入tab / 插入選擇",
		"Wrap with div"			=> "封裝 &lt;div&gt;",
		"Wrap with span"		=> "封裝 &lt;span&gt;",
		"Wrap unwrap p"			=> "封裝 / 撤銷封裝 &lt;p&gt;",
		"Wrap unwrap a"			=> "封裝 / 撤銷封裝 &lt;a&gt;",
		"Wrap unwrap b"			=> "封裝 / 撤銷封裝 &lt;b&gt;",
		"Wrap unwrap i"			=> "封裝 / 撤銷封裝 &lt;i&gt;",
		"Wrap unwrap strong"		=> "封裝 / 撤銷封裝 &lt;strong&gt;",
		"Wrap unwrap em"		=> "封裝 / 撤銷封裝 &lt;em&gt;",
		"Wrap unwrap li"		=> "封装 / 撤销封装 &lt;li&gt;",
		"Wrap unwrap h1..."		=> "封裝 / 撤銷封裝 &lt;h1&gt; - &lt;h3&gt;",
		"End line with..."		=> "換行 &lt;br&gt;",
		"Close tab"			=> "關閉選項卡",
		"Select file folder"		=> "選擇文件 / 文件夾",
		"Open file"			=> "打開文件",
		"Range select"			=> "範圍選擇",
		"Options for selected"		=> "所選的選項",
		"Delete selected"		=> "刪除已選",
		"Next previous tab"		=> "下一個 / 上一個 選項卡",
		"Next tab"			=> "下一個選項卡",
		"Previous tab"			=> "上一個選項卡",
		"New tab"			=> "新建選項卡",
		"Close current tab"		=> "關閉當前選項卡",
		"Open file prompt"		=> "打開文件的提示",
		"Find"				=> "檢索",
		"Focus on Go..."		=> "光標定位到轉到行的輸入框",
		"Save"				=> "保存",
		"Save as"			=> "另存爲...",
		"View webpage"			=> "預覽網頁",
		"Contract expand file..."	=> "收縮 / 擴展文件管理器",
		"Fold unfold current..."	=> "摺叠 / 展開當前行",
		"Refocus on document"		=> "光標重新聚焦到文檔",
		"Cancel tasks"			=> "取消任务"
	),

	"ice-coder" =>
	array(
		"No text selected..."		=> "搜索中沒有選中的文件",
		"Creating Folder"		=> "正在創建文件夾",
		"Sorry you can..."		=> "抱歉, 衹能同時打開100個文件!",
		"Opening File"			=> "正在打開文件",
		"Enter relative file..."	=> "輸入本地相對路徑 (前綴 /) 或遠程 URL",
		"Getting"			=> "正在獲取",
		"Please enter the..."		=> "請輸入新的名稱爲",
		"Renaming to"			=> "正在重命名",
		"Moving to"			=> "正在移動到",
		"Deleting File"			=> "正在刪除文件",
		"Pasting File"			=> "正在粘貼文件",
		"Sorry cannot paste..."		=> "抱歉, 無法粘貼到根路徑",
		"Nothing to paste..."		=> "粘貼失敗, 請先複製一個文件 / 文件夾!",
		"Replacing text in"		=> "正在替換文本",
		"Cancelled tasks"		=> "取消任務",
		"Open previous files"		=> "打開以前的文件?",
		"Please enter your..."		=> "請輸入您的 GitHub token (允許使用個人令牌或客戶端令牌). 可以查看 Github Auth Token 的幫助 > 更多信息在 幫助 > 設置窗口",
		"This will compare..."		=> "將比較和顯示您的本地目錄和 GitHub repo 之間的差異. 確定嗎?",
		"Please note for..."		=> "請注意: 需要更新才能工作, 您需要爲所有 ICEcoder 文件和文件夾設置寫如何刪除權限. 如果您需要恢復 ICEcoder 到這個版本, 您可以在 /tmp 目錄找到它們. 點擊 OK 繼續使用自動升級或點擊 cancel 訪問 ICEcoder 官方網站, 您也可以到 GitHub 的項目首頁下載 zip 來手動進行更新.",
		"You can start..."		=> "您可以在 幫助 > 設置中啓動錯誤報告",
		"Error cannot find..."		=> "錯誤: 無法找到和進入錯誤日志文件路徑",
		"No new errors..."		=> "沒有新的錯誤被找到",
		"You have made..."		=> "您已做的更改未保存。您確定要關閉它而不保存嗎?",
		"Close all tabs"		=> "是否關閉所有選項卡?"
	),

	"login" =>
	array(
		"set password"			=> "設置密碼",
		"login"				=> "登錄",
		"To disable registration..."	=> "要禁用注冊模式, 請進入選項或打開文件 lib/config___settings.php 并更改 enableRegistration 爲 false , 并重新載入本頁面",
		"Registration mode enabled"	=> "注冊模式已啓用",
		"auto-check for updates"	=> "自動檢測更新",
		"To put into..."		=> "要禁用多用戶模式, 請進入選項或打開文件 lib/config___settings.php 并更改 multiUser 爲 true , 并重新載入本頁面",
		"multi-user"			=> "多用戶"
	),

	"multiple-results" =>
	array(
		"rename all"			=> "重命名所有",
		"replace all"			=> "全部替換",
		"document"			=> "文檔",
		"Found"				=> "找到",
		"times"				=> "時間",
		"replace"			=> "替換",
		"file folder"			=> "文件 / 文件夾",
		"rename to"			=> "重命名爲",
		"rename"			=> "重命名",
		"file"				=> "文件",
		"No matches found"		=> "沒有找到匹配",
		"selected"			=> "選擇",
		"found in"			=> "查詢結果在",
		"Replaced"			=> "已替換"
	),

	"plugins-manager" =>
	array(
		"ICEcoder needs to..."		=> "ICEcoder 需要重新加載, 這個插件才可以使用。現在刷新嗎?",
		"saving plugins"		=> "正在保存插件...",
		"Cannot update config..."	=> "不能更新配置文件. 請爲",
		"and try again"			=> "設置寫入權限并刷新重試",
		"couldnt delete dir"		=> "不能刪除目錄",
		"couldnt delete file"		=> "不能刪除文件",
		"plugins"			=> "插件",
		"Guide to writing..."		=> "編寫插件指南",
		"Manage Installed"		=> "管理已安裝",
		"URL"				=> "URL",
		"Target"			=> "目標",
		"Timer"				=> "定時器",
		"Update"			=> "更新",
		"Install"			=> "安裝",
		"Uninstall"			=> "卸載",
		"Reload after install..."	=> "安裝后需要刷新"
	),

	"properties" =>
	array(
		"properties"			=> "屬性",
		"Size"				=> "尺寸",
		"Modified"			=> "修改",
		"Last access"			=> "最近訪問",
		"Type"				=> "類型",
		"Readable Writeable"		=> "可讀 / 可寫",
		"Relative path"			=> "相對路徑",
		"Absolute path"			=> "絕對路徑",
		"Contains"			=> "包含",
		"Permissions"			=> "權限",
		"Owner"				=> "所有者",
		"Group"				=> "組",
		"Public"			=> "公衆",
		"Read"				=> "讀取",
		"Write"				=> "寫入",
		"Execute"			=> "執行",
		"Change to"			=> "更改爲",
		"update"			=> "更新"
	),

	"settings-common" =>
	array(
		"Your document does..."		=> "你的文檔不是 UTF-8 編碼, 它將被轉換"
	),

	"settings-save-current-files" =>
	array(
		"Cannot update config..."	=> "不能更新配置文件. 請爲",
		"and try again"			=> "增加寫入權限并再次嘗試"
	),

	"settings-screen" =>
	array(
		"settings"			=> "設置",
		"version"			=> "版本",
		"website"			=> "網站",
		"git"				=> "git",
		"codemirror dir"		=> "codemirror 目錄",
		"codemirror version"		=> "codemirror 版本",
		"file manager root"		=> "文件管理器的根目錄 ROOT",
		"Free to use..."		=> "您可以免費使用它, 無論商業與否, 衹需讓我知道任何很酷的或有定製的:)同樣我們無任何責任和任何擔保, 使用所有的責任都是你的. 很多個人和公司爲 ICEcoder 作出過貢獻, 在此篇幅有限無法一一列舉, 請訪問貢獻者詳細列表",
		"functionality"			=> "功能",
		"check for updates..."		=> "啓動時檢查更新",
		"auto open last..."		=> "啓動時自動載入最后打開的文件",
		"when finding in..."		=> "在查找文件時, 排除",
		"assisting"			=> "輔助",
		"code assist"			=> "代碼輔助",
		"visible tabs"			=> "縮進綫可見",
		"locked nav"			=> "鎖定導航",
		"tag wrapper command"		=> "標簽封裝命令",
		"auto-complete on"		=> "打開自動完成",
		"security"			=> "安全",
		"new password"			=> "新密碼",
		"8 chars min"			=> "至少8個字符",
		"confirm password"		=> "確認密碼",
		"banned files/folders"		=> "禁止文件 / 文件夾",
		"banned paths"			=> "禁止路徑",
		"ip addresses"			=> "ip 地址",
		"Slash prefixed comma..."	=> "斜綫前綴, 以半角逗號分隔",
		"Comma delimited"		=> "以半角逗號分隔",
		"style"				=> "樣式",
		"theme"				=> "主題",
		"line wrapping"			=> "換行",
		"indent type"			=> "縮進類型",
		"indent size"			=> "縮進大小",
		"font size"			=> "字體大小",
		"auto indent"			=> "auto indent",
		"layout"			=> "佈局",
		"plugin panel aligned"		=> "插件版對齊",
		"file manager"			=> "文件管理器",
		"root"				=> "root",
		"Slash prefixed"		=> "斜綫前綴",
		"bug reporting"			=> "報告錯誤",
		"check in files"		=> "檢查文件",
		"every"				=> "任何",
		"secs getting last"		=> "秒, 最後得到",
		"lines"				=> "行",
		"multi-user"			=> "多用戶",
		"Make sure you..."		=> "確保你沒有把自己鎖了",
		"Registration"			=> "啓用注冊",
		"auth token"			=> "auth token",
		"Required to get..."		=> "如需要得到差異列表, 提交您 GitHub 上托管的 repo 等. 如果你還沒有一個, 你可以使用:".PHP_EOL.PHP_EOL.
							"- 個人令牌訪問 (https://help.github.com/articles/creating-an-access-token-for-command-line-use), 或".PHP_EOL.
							"- 完整的客戶端/機密雙令牌 (http://developer.github.com/v3/oauth).".PHP_EOL.PHP_EOL.
							"我們不建議您在此處設置令牌, 更安全的方式是當 ICEcoder 要求輸入時將其隻保存在會話中.".PHP_EOL.PHP_EOL.
							"然而, 如果你在一個信賴和安全的環境下工作, 把它設置在這裏是更有效的.",
		"Sorry cannot commit..."	=> "抱歉, 在演示模式下不能提交設置",
		"update"			=> "更新"
	),

	"settings-update" =>
	array(
		"Cannot update config..."	=> "不能更新配置文件. 請爲",
		"and try again"			=> " 增加寫入權限并重新嘗試",
		"and press refresh"		=> " 增加寫入權限并重新嘗試"
	),

	"updater" =>
	array(
		"Update appears to..."		=> "更新似乎是成功的"
	)

);
?>