Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Obtener la ruta de la carpeta donde esta este archivo
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' 1. Ejecutar el servidor de Laravel de forma 100% oculta
WshShell.Run "php artisan serve --host 0.0.0.0", 0, false

' Esperar 3 segundos para que el servidor de Laravel responda
WScript.Sleep 3000

' 2. Abrir el navegador en la IP indicada
WshShell.Run "cmd /c start http://[IP_ADDRESS]", 0, false
