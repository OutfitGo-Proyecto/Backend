# Consumo Frontend API Resenas de Pagina

Este documento explica que debe hacer el frontend (Angular) para consumir la API publica de testimonios de portada.

## Endpoint

- Metodo: `GET`
- URL: `/api/resenas-pagina`
- Autenticacion: no requiere token (ruta publica)

## Que devuelve la API

La API devuelve hasta 3 resenas, solo las que tienen `visible_en_portada = true`, ordenadas de mas nuevas a mas antiguas.

Ejemplo de respuesta:

```json
[
  {
    "id": 12,
    "user_id": 5,
    "puntuacion": 5,
    "comentario": "Muy buena experiencia",
    "visible_en_portada": true,
    "created_at": "2026-04-13T10:00:00.000000Z",
    "updated_at": "2026-04-13T10:00:00.000000Z",
    "user": {
      "id": 5,
      "name": "Ana"
    }
  }
]
```

## Lo que debe implementar Angular

1. Crear interfaz para tipar la respuesta:

```ts
export interface ResenaPagina {
  id: number;
  user_id: number;
  puntuacion: number; // 1-5
  comentario: string;
  visible_en_portada: boolean;
  created_at: string;
  updated_at: string;
  user: {
    id: number;
    name: string;
  };
}
```

2. Crear servicio con `HttpClient` para llamar al endpoint:

```ts
getResenasPortada() {
  return this.http.get<ResenaPagina[]>(`${environment.apiUrl}/resenas-pagina`);
}
```

3. En el componente de home/portada:
- Llamar al servicio en `ngOnInit`.
- Guardar el array en una propiedad local (ejemplo: `resenasPortada`).
- Mostrar loading y estado vacio por si no hay resenas.

4. Renderizar en plantilla:
- Mostrar nombre del usuario: `resena.user.name`
- Mostrar comentario: `resena.comentario`
- Pintar estrellas con `resena.puntuacion` (1 a 5)

## Recomendaciones frontend

- Si la API falla, mostrar mensaje amable y no romper la portada.
- Evitar llamadas repetidas innecesarias (cache simple en servicio o resolver).
- Si en el futuro se quiere carrusel con mas de 3 items, habria que ampliar la API.

## Checklist rapido

- [ ] Existe `environment.apiUrl` apuntando al backend correcto.
- [ ] Se crea servicio para `/resenas-pagina`.
- [ ] Se tipa la respuesta con interfaz.
- [ ] Se muestra loading/error/empty state.
- [ ] Se renderiza nombre, comentario y puntuacion.

