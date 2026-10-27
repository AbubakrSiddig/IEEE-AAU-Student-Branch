import * as XLSX from 'xlsx';

/**
 * Sanitize value to prevent Excel Formula Injection (CWE-1236 / CSV Injection)
 * Formulas starting with =, +, -, @, \t, \r could execute arbitrary code when opened in spreadsheet software.
 */
function sanitizeCell(val) {
  if (typeof val === 'string') {
    if (/^[=+\-@\t\r]/.test(val.trim())) {
      return "'" + val;
    }
    return val;
  }
  return val;
}

function sanitizeData(data) {
  if (!Array.isArray(data)) return [];
  return data.map(row => {
    if (!row || typeof row !== 'object') return row;
    const cleanRow = {};
    for (const [k, v] of Object.entries(row)) {
      cleanRow[k] = sanitizeCell(v);
    }
    return cleanRow;
  });
}

export function exportToExcel(data, fileName = 'export', sheetName = 'Sheet1') {
  try {
    const safeData = sanitizeData(data);
    const worksheet = XLSX.utils.json_to_sheet(safeData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
    
    // Auto-fit column widths
    const maxProps = [];
    safeData.forEach(item => {
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
  const safeData = sanitizeData(data);
  const worksheet = XLSX.utils.json_to_sheet(safeData);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, sheetName);
  return XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
}
