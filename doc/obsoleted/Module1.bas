Attribute VB_Name = "Module1"
Sub CleanForScheduleTXT()
' GetDirectory function is obtained from http://www.j-walk.com/ss/excel/tips/tip29.htm
Dim Dir As String
Dir = GetDirectory("Please select a folder that COURSE*.XLS exist") + "\"

Dim Arr As Variant, I As Variant, fn As String, num As String

Arr = Array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "0B")

For Each I In Arr
    fn = Dir + "COURSE" + I + ".XLS"
    Workbooks.Open (fn)
    ActiveWorkbook.RunAutoMacros xlAutoOpen

    num = Replace(ActiveWorkbook.Name, "COURSE0", "")
    num = Replace(num, "COURSE", "")
    num = Replace(num, ".XLS", "")
    num = Replace(num, ".xls", "")
    
    Rows("1:1").Select
    Selection.Delete Shift:=xlUp
    Columns("A:A").Select
    Selection.Insert Shift:=xlToRight

    RowCount = ActiveSheet.UsedRange.Rows.Count
    ColumnCount = ActiveSheet.UsedRange.Columns.Count
    Cells(1, 1).Value = num
    ActiveSheet.Range(Cells(1, 1), Cells(RowCount, 1)).FillDown
    ActiveSheet.Range(Cells(1, 1), Cells(RowCount, ColumnCount)).Select

    toSave = Dir + num + ".txt"
    ActiveWorkbook.SaveAs Filename:=toSave, FileFormat:=xlCurrentPlatformText
    Workbooks(num + ".txt").Close SaveChanges:=False
Next I
End Sub
