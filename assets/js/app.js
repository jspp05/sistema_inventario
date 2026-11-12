document.addEventListener('DOMContentLoaded', () => {
  initFiltros();
  initValidacionFormulario();
});

/* ===== Req. 1 y 3: búsqueda en tiempo real + filtro por estado + contador ===== */
function initFiltros() {
  const tabla = document.getElementById('tabla-productos');
  if (!tabla) return;

  const buscador = document.getElementById('buscador');
  const btnLimpiar = document.getElementById('btn-limpiar');
  const selectEstado = document.getElementById('filtro-estado');
  const contador = document.getElementById('contador');
  const sinResultados = document.getElementById('sin-resultados');
  const filas = tabla.querySelectorAll('tbody tr');

  function aplicarFiltros() {
    try {
      const texto = buscador.value.trim().toLowerCase();
      const estado = selectEstado.value;
      let visibles = 0;

      filas.forEach(fila => {
        const coincideTexto = fila.dataset.nombre.includes(texto) || fila.dataset.categoria.includes(texto);
        const coincideEstado = estado === 'todos' || fila.dataset.estado === estado;
        const mostrar = coincideTexto && coincideEstado;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
      });

      contador.textContent = `Mostrando ${visibles} producto(s)`;
      sinResultados.classList.toggle('oculto', visibles !== 0);
    } catch (error) {
      console.error('Error al filtrar los productos:', error);
      contador.textContent = 'No fue posible aplicar el filtro.';
    }
  }

  buscador.addEventListener('input', aplicarFiltros);
  selectEstado.addEventListener('change', aplicarFiltros);
  btnLimpiar.addEventListener('click', () => {
    buscador.value = '';
    selectEstado.value = 'todos';
    aplicarFiltros();
    buscador.focus();
  });
}

/* ===== Req. 2: validaciones del formulario (registro y edición) ===== */
function initValidacionFormulario() {
  const form = document.getElementById('form-producto');
  if (!form) return;

  const nombre = document.getElementById('nombre');
  const precio = document.getElementById('precio');
  const cantidad = document.getElementById('cantidad');
  const caja = document.getElementById('mensaje-error');

  form.addEventListener('submit', evento => {
    const errores = [];
    [nombre, precio, cantidad].forEach(c => c.classList.remove('campo-error'));

    if (nombre.value.trim() === '') {
      errores.push('El nombre es obligatorio.');
      nombre.classList.add('campo-error');
    }
    if (precio.value === '' || Number(precio.value) <= 0) {
      errores.push('El precio debe ser un número positivo.');
      precio.classList.add('campo-error');
    }
    if (cantidad.value === '' || !Number.isInteger(Number(cantidad.value)) || Number(cantidad.value) < 0) {
      errores.push('La cantidad debe ser un número entero no negativo.');
      cantidad.classList.add('campo-error');
    }

    if (errores.length) {
      evento.preventDefault();
      caja.innerHTML = errores.map(e => `<p>${e}</p>`).join('');
      caja.classList.remove('oculto');
    } else {
      caja.classList.add('oculto');
    }
  });
}
