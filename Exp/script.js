document.addEventListener("DOMContentLoaded", function() {
    // Define the LaTeX expression you want to render
    const inputText = `\\int_{0}^{x} e^{-t^2} \\, dt`; // Use double backslashes

    // Get the output div
    const outputDiv = document.getElementById('math-output');
    
    // Render the math using KaTeX
    katex.render(inputText, outputDiv, {
        throwOnError: false // Prevent errors from breaking the rendering
    });
});