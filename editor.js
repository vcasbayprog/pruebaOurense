const stage = new Konva.Stage({
    container: 'editorCanvas',
    width: 595, // A4 
    height: 842 // A4 
});

const layer = new Konva.Layer();
stage.add(layer);

// Añadir texto
function addText() {
    const text = document.getElementById('textInput').value;

    if (text.trim() === "") {
        alert("Por favor, ingresa un texto.");
        return;
    }

    const textNode = new Konva.Text({
        x: 50,
        y: 50,
        text: text,
        fontSize: 24,
        fontFamily: 'Calibri',
        fill: 'black',
        draggable: true,
        fontStyle: 'normal'
    });

    // Cambiar estilo a negrita al hacer doble clic
    textNode.on('dblclick', () => {
        const currentFontStyle = textNode.fontStyle();
        textNode.fontStyle(currentFontStyle === 'bold' ? 'normal' : 'bold');
        layer.batchDraw();
    });

    layer.add(textNode);
    layer.draw();
}

// Descargar el contenido como PDF
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', [595, 842]);

    layer.getChildren().forEach(child => {
        if (child instanceof Konva.Text) {
            const { x, y } = child.position();
            doc.setFont("helvetica");
            doc.setFontSize(child.fontSize());
            doc.setTextColor(child.fill());
            doc.setFont("helvetica", child.fontStyle());
            doc.text(child.text(), x, y);
        }
    });

    doc.save('resultado.pdf');
}

// Descargar el contenido como JSON
function downloadJSON() {
    const elements = layer.getChildren().map(child => {
        if (child instanceof Konva.Text) {
            return {
                text: child.text(),
                position: { x: child.x(), y: child.y() },
                style: {
                    fontSize: child.fontSize(),
                    fill: child.fill(),
                    fontStyle: child.fontStyle()
                }
            };
        }
    }).filter(Boolean);

    const blob = new Blob([JSON.stringify(elements, null, 2)], { type: 'application/json' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'resultado.json';
    link.click();



}
