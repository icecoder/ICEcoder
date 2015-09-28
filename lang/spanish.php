<?php
// Spanish language translation
// by: @unix4you2 (GitHub)

// Please preserve formatting, line breaks, special characters, anything in <tags> and HTML equivalents (eg &amp;). Translations on right side.

$text = array(

// / [ROOT LEVEL]

	"editor" =>
	array(
		"Click icons for..."		=> "<b>Clic en los iconos<br>para ayuda &amp;<br>modo de uso</b>",
		"server"			=> "servidor",
		"Server name, OS..."		=> "Nombre del servidor, OS & IP:",
		"Root"				=> "Raiz:",
		"ICEcoder root"			=> "Raiz de ICEcoder:",
		"PHP version"			=> "Versi&oacute;n PHP:",
		"Date & time"			=> "Fecha y hora:",
		"your device"			=> "su dispositivo",
		"Browser"			=> "Navegador:",
		"Your IP"			=> "Su IP:",
		"trial remaining"		=> "prueba restante",
		"days left"			=> "dias",
		"files"				=> "archivos",
		"Last 10 files..."		=> "Ultimos 10 archivos abiertos:",
		"none"				=> "[ninguno]",
		"test suite"			=> "modo de pruebas",
		"Run unit tests"		=> "Ejecutar tests unitarios",
		"dev mode"			=> "modo desarrollo",
		"Status"			=> "Estado",
		"Using"				=> "Usando",
		"You can switch..."		=> "Usted puede cambiar el modo de desarrollo cambiando su valor on/off en lib/config__settings.php"

	),

	"files" =>
	array(
		"Lock"				=> "Bloquear",
		"Refresh"			=> "Actualizar",
		"Plugins"			=> "Complementos",
		"ROOT"				=> "[RAIZ]"

	),

	"index" =>
	array(
		"UPDATE INFO"			=> "INFORMACION DE ACTUALIZACION",
		"now available"			=> "ahora disponible",
		"Your version is"		=> "Su versi&oacute;n es",
		"Update now"			=> "Actualizar ahora",
		"You have some..."		=> "Usted tiene cambios sin guardar",
		"Are you sure..." => "Est&aacute; seguro que desea cerrar?",
		"working"			=> "trabajando",
		"Color picker"			=> "Selector de color",
		"Plugins Manager"		=> "Administrar complementos",
		"New File"			=> "Nuevo archivo",
		"New Folder"			=> "Nueva carpeta",
		"Upload File(s)"		=> "Cargar archivo(s)",
		"Paste"				=> "Pegar",
		"Open"				=> "Abrir",
		"Copy"				=> "Copiar",
		"Duplicate"			=> "Duplicar",
		"Delete"			=> "Eliminar",
		"Rename"			=> "Renombrar",
		"View Webpage"			=> "Ver p&aacute;gina web",
		"Download"			=> "Descargar",
		"Properties"			=> "Propiedades",
		"File"				=> "Archivo",
		"Edit"				=> "Editar",
		"Source"			=> "Fuente",
		"Help"				=> "Ayuda",
		"Save"				=> "Guardar",
		"Save As"			=> "Guardar como",
		"Live Preview"			=> "Vista previa",
		"Upload"			=> "Cargar",
		"Zip"				=> "Comprimir",
		"Print"				=> "Imprimir",
		"Fullscreen toggle"		=> "Cambiar a pantalla completa",
		"Logout"			=> "Salir",
		"Undo"				=> "Deshacer",
		"Redo"				=> "Rehacer",
		"Indent more"			=> "Aumentar sangr&iacute;a",
		"Indent less"			=> "Disminuir sangr&iacute;a",
		"Autocomplete"			=> "Autocompletar",
		"Comment/Uncomment"		=> "Comentar/Descomentar",
		"Jump to Definition"		=> "Saltar a la definici&oacute;n",
		"Manual"			=> "Manual",
		"Shortcuts"			=> "Accesos directos",
		"Settings"			=> "Configuraci&oacute;n",
		"Search for selected"		=> "Buscar por lo seleccionado",
		"website"			=> "sitio web",
		"Close all tabs"		=> "Cerrar todas las pesta&ntilde;as",
		"Alphabetize tabs"		=> "Ordenar pesta&ntilde;as",
		"Find"				=> "Buscar",
		"in"				=> "en",
		"and"				=> "y",
		"replace"			=> "reemplazar",
		"replace all"			=> "reemplazar todo",
		"this document"			=> "este documento",
		"open documents"		=> "abrir documentos",
		"all files"			=> "todos los archivos",
		"all filenames"			=> "todos los nombres de archivo",
		"Turn on/off..."		=> "Encender o apagar asistente de c&oacute;digo",
		"Code Assist"			=> "Asistente de c&oacute;digo",
		"Go to Line"			=> "Ir a la l&iacute;nea",
		"View"				=> "Ver",
		"Bug reporting not active"	=> "Reporte de Errores no activado",
		"Single pane"			=> "Panel unico",
		"Diff pane also"		=> "Panel de diferencias tambien"
	),

// /LIB

	"bug-files-check" =>
	array(
		"Found in"			=> "Encontrado en:"
	),

	"file-control" =>
	array(
		"Sorry, bad filename..."	=> "Lo siento, nombre de archivo incorrecto. Verifique la consola de desarrollo para mas informacion?",
		"Sorry"				=> "Lo siento",
		"does not seem..."		=> "parace no existir en el servidor",
		"Sorry, could not..."		=> "Lo siento, no puedo obtener el contenido de",
		"Sorry, cannot create..."	=> "Lo siento, no puedo crear carpeta en",
		"Sorry, cannot copy"		=> "Lo siento, no puedo copiar",
		"into"				=> "into",
		"Uploaded file(s) OK"		=> "Carga de archivo(s) OK",
		"Sorry, cannot upload"		=> "Lo siento, no se puede cargar",
		"Sorry, cannot upload..."	=> "Lo siento, no se puede cargar en modo demostraci&oacute;n",
		"Sorry, cannot rename"		=> "Lo siento, no puedo renombrar",
		"Maybe public write..."		=> "Posiblemente sea necesario permisos publicos en esta carpeta o su carpeta padre?",
		"Sorry, cannot move"		=> "Lo siento, no puedo mover",
		"Sorry, cannot save"		=> "Lo siento, no puedo guardar",
		"Sorry, cannot replace..."	=> "Lo siento, no puedo reemplazar texto en",
		"Sorry, cannot change..."	=> "Lo siento, no puedo cambiar permisos sobre",
		"Sorry, cannot delete more..."	=> "Lo siento, no puedo eliminar mas de un item a la vez bajo modo FTP",
		"Sorry, cannot delete..."	=> "Lo siento, no puedo eliminar el nivel raiz",
		"Sorry, cannot delete"		=> "Lo siento, no puedo eliminar",
		"Sorry, this file..."		=> "Lo siento, este archivo ha cambiado, no puedo almacenar",
		"Reload this file..."		=> "Recargar este archivo y copiar su version en un panel diferente?",
		"There was a..."		=> "Hay un problema t&eacute;cnico, como algo que todav&iacute;a parece no estar listo. ICEcoder volvi&oacute; a cargar su archivo de control nuevamente.",
		"displayed at"			=> "Mostrado en",
		"Enter filename to..."		=> "Ingrese el nombre de archivo para guardar",
		"That file exists..."		=> "Ese archivo ya existeThat, sobreescribir?",
		"Saving"			=> "Guardando"
	),

	"get-branch" =>
	array(
		"There are no..."		=> "No hay diferencias entre el repositorio local y GitHub. Regresar al modo regular?",
		"Sorry, there was..."		=> "Lo siento, hay un error, c&oacute;digo:",
		"Your local folder..."		=> "Su carpeta local est&aacute; vac&iacute;a, desea clonarla"
	),

	"github-manager" =>
	array(
		"Sorry, cannot create..."	=> "Lo siento, no puedo crear un archivo en",
		"Cannot update config..."	=> "No puedo actualizar el archivo de configuraci&oacute;n. Por favor establezca permisos publicos para",
		"and try again"			=> "e intente nuevamente",
		"saving github paths"		=> "almacenando rutas de GitHub...",
		"github paths"			=> "Rutas de GitHub",
		"Choose existing path"		=> "Seleccionar una ruta existente",
		"Local path"			=> "Ruta local",
		"Slash prefixed"		=> "Prefijo de slash",
		"Remote GitHub path"		=> "Ruta remota GitHub",
		"Absolute URL beginning..."	=> "URL absoluta, iniciando https://github.com",
		"Choose"			=> "Seleccionar",
		"Set local and..."		=> "Establecer las rutas locales y remotas en blanco para removerlas",
		"Update"			=> "Actualizar",
		"Add new path"			=> "Agregar nueva ruta",
		"Add"				=> "Agregar",
		"Usage Info"			=> "Modo de uso:",
		"Enter relative local..."	=> "Ingrese las rutas locales relativas (ej /server/myfiles) y las rutas absolutas para GitHub (ej https://github.com/user/repo &oacute; https://github.com/user/repo/tree/branch para indicar ramas), seg&uacute;n los ejemplos. Con esto usted establece las rutas de fuentes para ambas ubicaciones, como un par.",
		"You can then..."		=> "Entonces usted podr&aacute; seleccionar un par y este se convertira en su nueva raiz de trabajo en ICEcoder.",
		"The file manager..."		=> "Entonces el administrador de archivos muestra un nuevo icono para GitHub, el cual usted puede usar para ver y verificar diferencias entre las dos rutas. Esas diferencias pueden ser convertidas a un commit y enviadas a la ruta remota en GitHub o clonadas en su ruta local, para sincronizar sus archivos.",
		"If you want..."		=> "Si lo desea puede crear una nueva ruta ra&iacute;z por medio de la pantalla de Configuraci&oacute;n."
	),

	"github" =>
	array(
		"Sorry, you do..."		=> "Lo siento, usted no parece contar con OpenSSL cargado en su instancia de PHP, por lo tanto conexiones https no est&aacute;n disponibles. Esto es requerido para la transferencia de datos a GitHub, por favor ajuste su php.ini, reinicie su servidor web e intente nuevamente"
	),

	"headers" =>
	array(
		"Bad CSRF token..."		=> "Token CSRF incorrecto. Por favor reporte este error en https://github.com/mattpass/ICEcoder para que pueda ser solucionado."
	),

	"help" =>
	array(
		"shortcuts"			=> "accesos directos",
		"Within document"		=> "En los documentos",
		"On Tabs"			=> "En las fichas",
		"Within file manager"		=> "En el administrador de archivos",
		"Anywhere"			=> "Cualquier parte",
		"Space"				=> "Espacio",
		"Click"				=> "Click",
		"or"				=> "o",
		"Left click"			=> "Click izquierdo",
		"Middle click"			=> "Click central",
		"Double click tap..."		=> "Doble click / tap (m&oacute;viles)",
		"Right click"			=> "Click derecho",
		"Middle scrollwheel"		=> "Rueda del rat&oacute;n",
		"Drag"				=> "Arrastrar",
		"Autocomplete add snippet"	=> "Autocompletar / agregar fragmento",
		"Multiple select"		=> "Selecci&oacute;n m&uacute;ltiple",
		"Move line up"			=> "Mover l&iacute;nea arriba",
		"Move line down"		=> "Mover l&iacute;nea abajo",
		"Duplicate lines"		=> "Duplicar linea(s)",
		"Remove lines"			=> "Remover linea(s)",
		"Insert line before"		=> "Insertar linea antes",
		"Insert line after"		=> "Insertar linea despu&eacute;s",
		"Search for selected"		=> "Buscar lo seleccionado",
		"Jump to definition"		=> "Saltar a definici&oacute;n / saltar atr&aacute;s",
		"Comment uncomment"		=> "Comentar / descomentar",
		"Insert tab indent"		=> "Insertar ficha / auto sangr&iacute;a seleccionado",
		"Insert more"			=> "Aumentar sangr&iacute;a",
		"Insert less"			=> "Disminuir sangr&iacute;a",
		"Wrap with div"			=> "Envolver con &lt;div&gt;",
		"Wrap with span"		=> "Envolver con &lt;span&gt;",
		"Wrap unwrap p"			=> "Envolver / Desenvolver con &lt;p&gt;",
		"Wrap unwrap a"			=> "Envolver / Desenvolver con &lt;a&gt;",
		"Wrap unwrap b"			=> "Envolver / Desenvolver con &lt;b&gt;",
		"Wrap unwrap i"			=> "Envolver / Desenvolver con &lt;i&gt;",
		"Wrap unwrap strong"		=> "Envolver / Desenvolver con &lt;strong&gt;",
		"Wrap unwrap em"		=> "Envolver / Desenvolver con &lt;em&gt;",
		"Wrap unwrap li"		=> "Envolver / Desenvolver con &lt;li&gt;",
		"Wrap unwrap h1..."		=> "Envolver / Desenvolver con &lt;h1&gt; - &lt;h3&gt;",
		"End line with..."		=> "Terminar l&iacute;nea con &lt;br&gt;",
		"Close tab"			=> "Cerrar ficha",
		"Select file folder"		=> "Seleccionar archivo / carpeta",
		"Open file"			=> "Abrir archivo",
		"Range select"			=> "Seleccionar rango",
		"Options for selected"		=> "Opciones para lo seleccionado",
		"Delete selected"		=> "Eliminar seleccionados",
		"Next previous tab"		=> "Ficha siguiente / previa",
		"Next tab"			=> "Ficha siguiente",
		"Previous tab"			=> "Ficha previa",
		"New tab"			=> "Nueva ficha",
		"Close current tab"		=> "Cerrar ficha actual",
		"Open file prompt"		=> "L&iacute;nea de apertura de archivos",
		"Find"				=> "Buscar",
		"Previous"			=> "Previo",
		"Focus on Go..."		=> "Obtener foco en la opci&oacute;n Ir a Linea",
		"Save"				=> "Guardar",
		"Save as"			=> "Guardar como...",
		"View webpage"			=> "Ver pagina web",
		"Contract expand file..."	=> "Contraer / expandir administrador de archivos",
		"Fold unfold current..."	=> "Contraer / expandir l&iacute;nea actual",
		"Refocus on document"		=> "Reasignar foco al documento",
		"Cancel tasks"			=> "Cancelar tareas"
	),

	"ice-coder" =>
	array(
		"results"			=> "resultados",
		"No text selected..."		=> "No hay texto seleccionado para buscar",
		"all files"			=> "todos los archivos",
		"all filenames"			=> "todos los nombres de archivo",
		"selected files"		=> "archivos seleccionados",
		"selected filenames"		=> "nombres de archivos seleccionados",
		"Creating Folder"		=> "Creando Carpeta",
		"Sorry you can..."		=> "Lo siento, usted s&oacute;lo puede tener 100 archivos abiertos al tiempo!",
		"Opening File"			=> "Abriendo archivo",
		"Enter relative file..."	=> "Entre una ruta relativa (iniciando con /) o una URL remota",
		"Getting"			=> "Obteniendo",
		"Saving"			=> "Guardando:",
		"Please enter the..."		=> "Por favor ingrese el nuevo nombre para",
		"Renaming to"			=> "Renombrando a",
		"Moving to"			=> "Moviendo a",
		"Deleting File"			=> "Eliminando Archivo",
		"Pasting File"			=> "Copiando Archivo",
		"Sorry cannot paste..."		=> "Lo siento, no puedo pegar toda una ra&iacute;z",
		"Nothing to paste..."		=> "Nada para pegar, copie un archivo/carpeta primero!",
		"and"				=> "y",
		"this document"			=> "este documento",
		"replace"			=> "reemplazar",
		"replace all"			=> "reemplazar todo",
		"file"				=> "archivo",
		"Replacing text in"		=> "Reemplazando texto en",
		"Sorry there was..."		=> "Lo siento, hubo un error con su solicitud.\\n\\nPor favor verifique su consola de desarrollo para mas informacion.",
		"Cancelled tasks"		=> "Tareas canceladas",
		"Open previous files"		=> "Abrir archivos previos?",
		"Please enter your..."		=> "Por favor entre su token de GitHub (para acceso personal). Ver ayuda cerca al Token de autenticaci&oacute;n de Github en la ayuda > Vea pantalla de configuraci&oacute;n para m&aacute;s informaci&oacute;n",
		"This will compare..."		=> "Esto compara y muestra las diferencias entre su ruta local y el repositorio. OK?",
		"Please note for..."		=> "Importante: para que la actualizaci&oacute;n trabaje correctamente, usted necesita tener derechos de acceso a todos los directorios y archivos de ICEcoder. Esto sera chequeado previamente y se presentara una lista de archivos sin acceso (por lo tanto no movibles).\\n\\nSi usted necesita restablecer esta version de  ICEcoder por alguna raz&oacute;n, usted la encontrara en la carpeta /tmp.\\n\\nClick en OK para proceder con la verificaci&oacute;n y auto-instalaci&oacute;n, &oacute; cancelar para visitar el sitio de ICEcoder para descargar el ZIP y hacerlo manualmente.",
		"You can start..."		=> "Usted puede iniciar el reporte de errores en la pantalla de Ayuda > Configuraci&oacute;n",
		"Error cannot find..."		=> "Error: no puedo encontrar o accesar las rutas de archivos de error",
		"No new errors..."		=> "No fueron encontrados nuevos errores",
		"You have made..."		=> "Usted ha realizado cambios. Esta seguro que quiere cerrar sin guardar?",
		"Close all tabs"		=> "Cerrar todas las fichas?"
	),

	"login" =>
	array(
		"set password"			=> "establecer clave",
		"login"				=> "ingreso",
		"To disable registration..."	=> "Para deshabilitar el modo de registro, vaya a la opci&oacute;n de Configuraci&oacute;n or abra el archivo lib/config___settings.php y cambie enableRegistration a false y recargue la p&aacute;gina",
		"Registration mode enabled"	=> "Modo de registro activado",
		"auto-check for updates"	=> "auto-buscar por actualizaciones",
		"To put into..."		=> "Para usar el modo multiusuario, vaya a la opcion de Configuraci&oacute;n o abra el archivo lib/config___settings.php y cambie multiUser a true y recargue la p&aacute;gina",
		"multi-user"			=> "multi-usuario"
	),

	"multiple-results" =>
	array(
		"rename all"			=> "renombrar todo",
		"replace all"			=> "reemplazar todo",
		"document"			=> "documento",
		"Found"				=> "Encontrado",
		"times"				=> "veces",
		"replace"			=> "reemplazar",
		"file folder"			=> "archivo/carpeta",
		"rename to"			=> "renombrar a",
		"rename"			=> "renombrar",
		"file"				=> "archivo",
		"No matches found"		=> "No se encontraron coincidencias",
		"selected"			=> "seleccionado",
		"found in"			=> "encontrado en",
		"Replaced"			=> "Reemplezado"
	),

	"plugins-manager" =>
	array(
		"ICEcoder needs to..."		=> "ICEcoder necesita recargar para activar este plugin. Recargar ahora?",
		"saving plugins"		=> "guardando plugins...",
		"Cannot update config..."	=> "No puedo actualizar el archivo de configuraci&oacute;n. Por favor establezca permisos publicos sobre",
		"and try again"			=> "e intente de nuevo",
		"couldnt delete dir"		=> "no puedo borrar el directorio",
		"couldnt delete file"		=> "no puedo borrar el archivo",
		"plugins"			=> "plugins",
		"Guide to writing..."		=> "Guia para escribir plugins",
		"Manage Installed"		=> "Administrador instalado",
		"URL"				=> "URL",
		"Target"			=> "Objetivo",
		"Timer"				=> "Temporizador",
		"Update"			=> "Actualizar",
		"Install"			=> "Instalar",
		"Uninstall"			=> "Desinstalar",
		"Reload after install..."	=> "Se requiere recargar despu&eacute;s de instalar"
	),

	"properties" =>
	array(
		"properties"			=> "propiedades",
		"Size"				=> "Tama&ntilde;o",
		"Modified"			=> "Modificado",
		"Last access"			=> "Ultimo acceso",
		"Type"				=> "Tipo",
		"Readable Writeable"		=> "Leible / Escribible",
		"Relative path"			=> "Ruta relativa",
		"Absolute path"			=> "Ruta absoluta",
		"Contains"			=> "Contiene",
		"Permissions"			=> "Permisos",
		"Owner"				=> "Propietario",
		"Group"				=> "Grupo",
		"Public"			=> "Publico",
		"Read"				=> "Leer",
		"Write"				=> "Escribir",
		"Execute"			=> "Ejecutar",
		"Change to"			=> "Cambiar a",
		"update"			=> "actualizar"
	),

	"settings-common" =>
	array(
		"Your document does..."		=> "Su documento no parece estar en UTF-8 entonces se ha convertido"
	),

	"settings-save-current-files" =>
	array(
		"Cannot update config..."	=> "No puedo actualizar el archivo de configuracion. Por favor establezca permisos publicos sobre",
		"and try again"			=> "e intente de nuevo"
	),

	"settings-screen" =>
	array(
		"settings"			=> "configuraciones",
		"version"			=> "version",
		"website"			=> "sitio web",
		"git"				=> "git",
		"codemirror dir"		=> "directorio codemirror",
		"codemirror version"		=> "version codemirror",
		"file manager root"		=> "administrador de archivos raiz",
		"Get in contact..."		=> "Pongase en contacto...",
		"backups"			=> "respaldos",
		"keep version control..."	=> "mantener control de versiones de respaldo para",
		"day"	=> "dia",
		"days"	=> "dias",
		"of backups stored..."	=> "de respaldos almacenados actualmente",
		"You may use..."		=> "Libre para usarlo para cualquier prop&oacute;sito, comercial o no, solo dejeme saber cualquier uso o mejora. :)<br><br>No se da garantia de ningun tipo, su uso se encuentra bajo su responsabilidad.",
		"functionality"			=> "funcionalidad",
		"check for updates..."		=> "verificar por actualizaciones en la carga",
		"auto open last..."		=> "auto cargar los ultimos archivos al ingresar",
		"when finding in..."		=> "excluir cuando se busca en archivos ",
		"assisting"			=> "asistir",
		"code assist"			=> "asistencia de c&oacute;digo",
		"visible tabs"			=> "Fichas visibles",
		"locked nav"			=> "Navegacion bloqueada",
		"tag wrapper command"		=> "Comando envuelto",
		"auto-complete on"		=> "auto-completar encendido",
		"security"			=> "seguridad",
		"new password"			=> "nueva clave",
		"8 chars min"			=> "8 caracteres min",
		"confirm password"		=> "confirmar clave",
		"banned files/folders"		=> "archivos/carpetas prohibidos",
		"banned paths"			=> "rutas prohibidas",
		"ip addresses"			=> "direcciones ip",
		"Slash prefixed comma..."	=> "Prefijo slash, delimitado por comas",
		"Comma delimited"		=> "Delimitado por comas",
		"style"				=> "estilo",
		"theme"				=> "tema",
		"line wrapping"			=> "ajuste de l&iacute;nea",
		"indent type"			=> "tipode sangr&iacute;a",
		"indent size"			=> "Tama&ntilde;o de sangr&iacute;a",
		"font size"			=> "tama&ntilde;o de letra",
		"layout"			=> "disposici&oacute;n",
		"plugin panel aligned"		=> "alineacion del panel de plugins",
		"file manager"			=> "administrador de archivos",
		"root"				=> "raiz",
		"Slash prefixed"		=> "Prefijo Slash",
		"bug reporting"			=> "reporte de errores",
		"check in files"		=> "chequear en archivos",
		"every"				=> "cada",
		"secs getting last"		=> "seg, obteniendo &uacute;ltimas",
		"lines"				=> "lineas",
		"multi-user"			=> "multi-usuario",
		"Make sure you..."		=> "Este seguro de no bloquearse a s&iacute; mismo",
		"Registration"			=> "Registro",
		"auth token"			=> "token autenticaci&oacute;n",
		"Required to get..."		=> "Requerido para obtener diferencias, enviar cambios a repos GitHub, etc. Si usted no tiene uno, usted puede usar un:".PHP_EOL.PHP_EOL.
							"- token personal de acceso (https://help.github.com/articles/creating-an-access-token-for-command-line-use), &oacute;".PHP_EOL.
							"- full client/secret pair token (http://developer.github.com/v3/oauth).".PHP_EOL.PHP_EOL.
							"Esto no es recomendado para establecer su token pues es mas seguro ingresarlo cuando sea solicitado por ICEcoder y as&iacute; permenecer&aacute; en la sesi&oacute;n activa unicamente.".PHP_EOL.PHP_EOL.
							"De todas formas, si usted trabaj en un ambiente seguro, es m&aacute;s eficiente establecer esto aqu&iacute;.",
		"Sorry cannot commit..."	=> "Lo siento, no puedo enviar configuraciones en modo demo",
		"update"			=> "actualizar"
	),

	"settings-update" =>
	array(
		"Cannot update config..."	=> "No puedo actualizar archivo de configuraci&oacute;n. Establezca permisos publicos sobre",
		"and try again"			=> "e intente de nuevo",
		"and press refresh"		=> "y presione actualizar"
	),

	"updater" =>
	array(
		"Update appears to..."		=> "La actualizaci&oacute;n parece haber sido satisfactoria"
	),

	"find-in-files" =>
	array(
		"Enter path to search in" => "Ingrese la ruta para buscar",
		"Enter semicolon-separated masks of files to look at (e.g. *.php;*.html;*.js)" => "Ingrese la mascaras de archivo separadas por punto y coma para buscar (ej. *.php;*.html;*.js)",
		"Type of text" => "Tipo de texto",
		"Fixed text" => "Texto fijo",
		"Regular expression" => "Expresion regular",
		"Case sensitive" => "Sensible a mayuscula",
		"Yes" => "Si",
		"No" => "No",
		"Search" => "Buscar",
	)

);
?>
