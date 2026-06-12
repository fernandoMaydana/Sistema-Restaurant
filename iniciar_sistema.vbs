Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Obtener la ruta de la carpeta donde esta este archivo
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' 1. Verificar si MySQL ya esta en ejecucion
Set service = GetObject("winmgmts:")
Set processes = service.ExecQuery("select * from Win32_Process where Name='mysqld.exe'")

If processes.Count = 0 Then
    ' Si no esta corriendo, iniciarlo de forma oculta desde la ruta por defecto de XAMPP
    If fso.FileExists("C:\xampp\mysql_start.bat") Then
        WshShell.CurrentDirectory = "C:\xampp"
        WshShell.Run "cmd.exe /c mysql_start.bat", 0, false
        WshShell.CurrentDirectory = strPath
        ' Esperar 2 segundos adicionales para asegurar que MySQL cargue antes de Laravel
        WScript.Sleep 2000
    End If
End If

' 2. Ejecutar el servidor de Laravel de forma 100% oculta
WshShell.Run "php artisan serve --host 0.0.0.0", 0, false

' Esperar 3 segundos para que el servidor de Laravel responda
WScript.Sleep 3000

' 3. Abrir el navegador en la IP indicada
WshShell.Run "cmd /c start http://192.168.0.7:8000", 0, false
