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

' Funcion para verificar si el servidor responde en http://127.0.0.1:8000
Function ServidorEstaListo()
    On Error Resume Next
    Set http = CreateObject("MSXML2.ServerXMLHTTP.6.0")
    http.open "GET", "http://127.0.0.1:8000", False
    http.setTimeouts 1000, 1000, 1000, 1000
    http.send
    If Err.Number = 0 Then
        ServidorEstaListo = True
    Else
        ServidorEstaListo = False
    End If
    On Error GoTo 0
End Function

' 1. Verificar si el servidor YA esta encendido
If Not ServidorEstaListo() Then
    ' Iniciar el servidor de Laravel de forma 100% oculta en segundo plano
    WshShell.Run "php artisan serve --host 0.0.0.0 --port 8000", 0, False

    ' 2. Esperar activamente (Loop) hasta que el puerto responda (maximo 15 segundos)
    Dim intentos, listo
    intentos = 0
    listo = False
    Do While intentos < 30 And Not listo
        WScript.Sleep 500
        intentos = intentos + 1
        If ServidorEstaListo() Then
            listo = True
        End If
    Loop
End If

' 3. Abrir el navegador automaticamente con la IP local detectada
WshShell.Run "cmd /c start http://" & strIP & ":8000", 0, False

