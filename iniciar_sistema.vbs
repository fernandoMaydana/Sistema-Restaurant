Set WshShell = CreateObject("WScript.Shell")

' Obtener la ruta de la carpeta donde esta este archivo
strPath = CreateObject("Scripting.FileSystemObject").GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' Ejecutar el servidor de Laravel de forma 100% oculta (el parametro 0 oculta la ventana)
WshShell.Run "php artisan serve --host 0.0.0.0", 0, false

' Esperar 3 segundos para que el servidor responda
WScript.Sleep 3000

' Abrir el navegador en localhost (para asegurar que siempre abra en la PC local aunque cambie la IP)
WshShell.Run "cmd /c start http://192.168.0.7:8000", 0, false
