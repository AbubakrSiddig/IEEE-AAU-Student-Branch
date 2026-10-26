import JSZip from 'jszip';
import { saveAs } from 'file-saver';
import { generateExcelBuffer } from './excelExport';
import { storageService } from './storage';

export async function downloadAllReportsZip() {
  const zip = new JSZip();

  // 1. Members Excel
  const members = storageService.getMembers().map(m => ({
    'Member ID': m.id,
    'Full Name': m.name,
    'Email Address': m.email,
    'Student Team': m.team,
    'Academic Year': m.year,
    'Role': m.role,
    'Status': m.status,
    'Join Date': m.joinDate
  }));
  zip.file('01_Members_Report.xlsx', generateExcelBuffer(members, 'Members'));

  // 2. Applications Excel
  const apps = storageService.getApplications().map(a => ({
    'Application ID': a.id,
    'Full Name': a.name,
    'Email': a.email,
    'Phone': a.phone || '',
    'Major': a.major,
    'College': a.college || '',
    'Year': a.year,
    'Status': a.status,
    'Applied Date': a.appliedOn,
    'Terms Version': a.termsVersion || 'v2.0'
  }));
  zip.file('02_Applications_Report.xlsx', generateExcelBuffer(apps, 'Applications'));

  // 3. Events Excel
  const events = storageService.getEvents().map(e => ({
    'Event ID': e.id,
    'Title': e.title,
    'Date': e.date,
    'Time': e.time,
    'Location': e.location,
    'Status': e.status,
    'Seats': e.seats,
    'Speakers': e.speakers
  }));
  zip.file('03_Events_Report.xlsx', generateExcelBuffer(events, 'Events'));

  // 4. Volunteers Excel
  const volunteers = storageService.getVolunteers().map(v => ({
    'Volunteer ID': v.id,
    'Name': v.name,
    'Email': v.email,
    'Phone': v.phone,
    'Team Preference': v.team,
    'Preferred Activities': (v.activities || []).join(', '),
    'Status': v.status,
    'Applied Date': v.appliedOn
  }));
  zip.file('04_Volunteers_Report.xlsx', generateExcelBuffer(volunteers, 'Volunteers'));

  // 5. Readme / Manifest
  zip.file('Manifest.txt', `IEEE AAU Student Branch — Official Reports Bundle
Generated: ${new Date().toLocaleString()}
Branch: Alzaeim Alazhari University Student Branch
Contains:
- Members Report (.xlsx)
- Applications Report (.xlsx)
- Events Report (.xlsx)
- Volunteers Report (.xlsx)
`);

  const content = await zip.generateAsync({ type: 'blob' });
  saveAs(content, `IEEE_AAU_SB_All_Reports_${new Date().toISOString().slice(0, 10)}.zip`);
}
