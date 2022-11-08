' Test "Re-Dim Preserve", with Multi-Dim Arrays...

    Dim as string Arr()

    Dim as Integer d1lb,d1ub,d2lb,d2ub,d3lb,d3ub
    Dim as Integer e1lb,e1ub,e2lb,e2ub,e3lb,e3ub
    Dim as Integer i1,i2,i3
    Dim as string  x

    d1lb=+1: d1ub=+3
    d2lb=+2: d2ub=+3
    d3lb=-1: d3ub=+1

    ReDim Preserve Arr(d1lb to d1ub, d2lb to d2ub, d3lb to d3ub)

    ' Fill the Array - with "slightly" random data, random order!!

    For i1 = d1lb to d1ub: For i2 = d3lb to d3ub: For i3 = d2lb to d2ub
        x = Str(i1) & "/" & Str(i3) & "/" & Str(i2)
        Arr(i1,i3,i2) = x
    Next i3: Next i2: Next i1

    ' Show it

    For i1 = d1lb to d1ub: For i2 = d2lb to d2ub: For i3 = d3lb to d3ub
        Print i1;i2;i3;" = ";Arr(i1,i2,i3)
    Next i3: Next i2: Next i1
    Print

    ' Re-Dim

    e1lb = d1lb+0: e1ub = d1ub-1    'Messup - Some important old cells DESTROYED!
    e2lb = d2lb+0: e2ub = d2ub+0
    e3lb = d3lb-0: e3ub = d3ub+0

    ReDim Preserve Arr(e1lb to e1ub, e2lb to e2ub, e3lb to e3ub)

    ' Show it

    For i1 = e1lb to e1ub: For i2 = e2lb to e2ub: For i3 = e3lb to e3ub
        Print i1;i2;i3;" = ";Arr(i1,i2,i3)
    Next i3: Next i2: Next i1

    Sleep:Close:End
