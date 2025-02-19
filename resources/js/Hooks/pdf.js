import {jsPDF} from "jspdf";
import html2canvas from "html2canvas";

export default {
  async generate(content, title) {
    const doc = new jsPDF('p', 'mm', 'a4');

    await html2canvas(content, { scale: 2 }).then(function (canvas) {
      const imgData = canvas.toDataURL('image/png');
      const imgWidth = 210;
      const pageHeight = 297;
      const imgHeight = (canvas.height * imgWidth) / canvas.width;
      let heightLeft = imgHeight;
      let position = 0;

      doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);

      heightLeft -= pageHeight;

      while (heightLeft >= 0) {
        position = heightLeft - imgHeight;
        doc.addPage();
        doc.addImage(imgData, 'PNG', 0, position + 40, imgWidth, imgHeight);
        heightLeft -= pageHeight;
      }

      doc.save(`${title}.pdf`);
    });
  },
}
