import { jsPDF } from 'jspdf';

export function downloadTermsPDF(version = 'v2.0', customText = null) {
  const doc = new jsPDF();

  // Header band
  doc.setFillColor(0, 40, 85); // IEEE Navy
  doc.rect(0, 0, 210, 35, 'F');

  // Title in header
  doc.setTextColor(255, 255, 255);
  doc.setFontSize(18);
  doc.setFont('helvetica', 'bold');
  doc.text('IEEE AAU Student Branch', 14, 18);
  
  doc.setFontSize(10);
  doc.setFont('helvetica', 'normal');
  doc.text('Alzaeim Alazhari University - Student Branch Official Bylaws', 14, 26);
  doc.text(`Version: ${version} | Effective: April 2025`, 140, 26);

  // Document Title
  doc.setTextColor(20, 30, 50);
  doc.setFontSize(14);
  doc.setFont('helvetica', 'bold');
  doc.text('Code of Conduct, Membership & Volunteer Guidelines', 14, 48);

  const defaultContent = `1. Introduction
These terms and conditions govern your participation in IEEE AAU Student Branch (Alzaeim Alazhari University) activities, events, and committees.

2. Membership and Participation
By registering, you agree to abide by the IEEE Code of Ethics, university student regulations, and IEEE AAU SB internal bylaws. Members are expected to actively participate in committee initiatives and branch meetings.

3. Code of Conduct & Respect
Mutual respect, professional integrity, and constructive teamwork are required. Harassment, discrimination, or divisive behavior will result in immediate termination of membership.

4. Confidentiality & Branch Assets
Branch organizational assets, media materials, and internal records must be kept confidential. Official representations and statements on behalf of the branch must receive prior clearance from the Branch Executive Committee.

5. Volunteer Obligations & Rights
Volunteers are expected to execute their assigned tasks diligently and adhere to committed timelines. Academic commitments take precedence: branch activities must never cause students to miss scheduled university lectures or exams. Active volunteers in good standing are entitled to official verified certificates of appreciation and leadership recognition.

6. Acknowledgment & Consent
By proceeding with the application, the applicant explicitly confirms full agreement to adhere to all IEEE AAU SB bylaws, policies, and subsequent administrative decisions.`;

  const textToPrint = customText || defaultContent;

  doc.setFontSize(10);
  doc.setFont('helvetica', 'normal');
  doc.setTextColor(50, 60, 75);

  const splitText = doc.splitTextToSize(textToPrint, 182);
  doc.text(splitText, 14, 58);

  // Footer
  const pageHeight = doc.internal.pageSize.height;
  doc.setDrawColor(220, 225, 230);
  doc.line(14, pageHeight - 20, 196, pageHeight - 20);
  doc.setFontSize(8);
  doc.setTextColor(120, 130, 140);
  doc.text('IEEE AAU SB | info@ieee-aau.org | Alzaeim Alazhari University, Khartoum, Sudan', 14, pageHeight - 12);
  doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 155, pageHeight - 12);

  doc.save(`IEEE_AAU_SB_Terms_${version}.pdf`);
}
