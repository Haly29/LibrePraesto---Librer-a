document.addEventListener('DOMContentLoaded', function () {
    // Obtén el contexto del canvas
    var ctx = document.getElementById('graficaLibros').getContext('2d');

    // Datos de ejemplo. Puedes modificar esto para que se cargue dinámicamente desde PHP.
    var datosLibros = {
        labels: ['Libro 1', 'Libro 2', 'Libro 3', 'Libro 4'], // Nombres de los libros
        datasets: [{
            label: 'Total Vendido',
            data: [15, 30, 22, 10], // Total de libros vendidos por cada título
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
            borderWidth: 1
        }]
    };

    // Crear la gráfica
    var myChart = new Chart(ctx, {
        type: 'bar', // Tipo de gráfica (barra)
        data: datosLibros,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});