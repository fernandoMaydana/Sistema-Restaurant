Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Obtener la ruta de la carpeta donde esta este archivo
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' Obtener automaticamente la IP local activa del equipo (Wi-Fi / Ethernet)
strIP = "localhost"

On Error Resume Next
Set objWMIService = GetObject("winmgmts:\\.\root\cimv2")
Set colItems = objWMIService.ExecQuery("Select * from Win32_NetworkAdapterConfiguration where IPEnabled = True")

For Each objItem in colItems
    If Not IsNull(objItem.IPAddress) Then
        For Each strAddress in objItem.IPAddress
            ' Filtrar IPv4 (evitando IPv6, loopback 127 y red local de enlace 169.254)
            If InStr(strAddress, ".") > 0 And Left(strAddress, 3) <> "127" And Left(strAddress, 7) <> "169.254" Then
                ' Priorizar la interfaz que tenga puerta de enlace (internet / red activa)
                If Not IsNull(objItem.DefaultIPGateway) Then
                    strIP = strAddress
                    Exit For
                ElseIf strIP = "localhost" Then
                    strIP = strAddress
                End If
            End If
        Next
    End If
Next
On Error GoTo 0

' 1. Ejecutar el servidor de Laravel de forma 100% oculta escuchando en todas las interfaces (0.0.0.0:8000)
WshShell.Run "php artisan serve --host 0.0.0.0 --port 8000", 0, false

' Esperar 3 segundos para que el servidor de Laravel responda
WScript.Sleep 3000

' 2. Abrir el navegador automaticamente con la IP local detectada
WshShell.Run "cmd /c start http://" & strIP & ":8000", 0, false
