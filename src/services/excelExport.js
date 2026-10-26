import * as XLSX from 'xlsx';

export function exportToExcel(data, fileName = 'export', sheetName = 'Sheet1') {
  try {
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
    
    // Auto-fit column widths
    const maxProps = [];
    data.forEach(item => {
      Object.keys(item).forEach((key, i) => {
        const valLen = String(item[key] || '').length;
        const keyLen = String(key).length;
        maxProps[i] = Math.max(maxProps[i] || 10, valLen, keyLen) + 2;
      });
    });
    worksheet['!cols'] = maxProps.map(w => ({ wch: w }));

    XLSX.writeFile(workbook, `${fileName}_${new Date().toISOString().slice(0, 10)}.xlsx`);
    return true;
  } catch (error) {
    console.error('Excel export error:', error);
    return false;
  }
}

export function generateExcelBuffer(data, sheetName = 'Sheet1') {
  const worksheet = XLSX.utils.json_to_sheet(data);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
  return XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
}
