import cv2
import pandas as pd
import numpy as np
from ultralytics import YOLO
import requests
from datetime import datetime
import time
import os
import mysql.connector
import urllib.request

config = {
  'user': 'albert',
  'password': '1234@',
  'host': 'localhost',
  'database': 'app',
  'raise_on_warnings': True
}

codParking = 1



# URL de la imagen de la cámara IP
url = 'http://192.168.1.255:4747/cam/1/frame.jpg'

# Cargar el modelo YOLO
model = YOLO('yolov8s.pt')

# Definir las áreas de interés
areas = [
    [(304, 17), (450, 42), (446, 86), (298, 49)],
    [(294, 52), (442, 89), (442, 119), (287, 92)],
    [(270, 94), (443, 112), (446, 166), (267, 144)],
    [(270, 146), (443, 171), (440, 220), (258, 204)],
    [(237, 208), (428, 227), (422, 300), (226, 285)],
    [(211, 296), (452, 300), (458, 401), (191, 380)],
    [(178, 377), (438, 411), (419, 499), (127, 489)],
    [(588, 70), (696, 81), (704, 112), (598, 112)],
    [(599, 117), (737, 112), (742, 140), (618, 152)],
    [(612, 152), (757, 148), (773, 188), (663, 201)],
    [(636, 212), (777, 196), (793, 236), (650, 248)],
    [(655, 260), (850, 240), (870, 306), (689, 328)],
    [(689, 328), (884, 308), (930, 374), (706, 391)],
    [(697, 392), (912, 385), (943, 463), (728, 479)]
    
]

# Leer el archivo de clases COCO
with open("coco.txt", "r") as my_file:
    class_list = my_file.read().split("\n")
    


# Función para conectarme a la base de datos y actualizar
def update_database(free_spaces, codParking):
    cnx = mysql.connector.connect(**config)
    cursor = cnx.cursor()
    
    update_free_spaces = ("UPDATE parking "
                          "SET disponibles_plazas = %s "
                          "WHERE codParking = %s")
    cursor.execute(update_free_spaces, (free_spaces, codParking))
    
    cnx.commit()

    cursor.close()
    cnx.close()

# Función para capturar y procesar la imagen
def capture_and_process_image():
    # Realiza la solicitud GET a la URL
    response = requests.get(url)

    if response.status_code == 200:
        # Nombre del archivo basado en la fecha y hora actual
        filename = datetime.now().strftime('%Y%m%d_%H%M%S') + '.jpg'

        # Guarda la imagen en el directorio actual
        with open(filename, 'wb') as f:
            f.write(response.content)

        print(f'Imagen guardada como {filename}')
        
        # Leer la imagen
        frame = cv2.imread(filename)
        frame = cv2.resize(frame, (1020, 500))

        # Realizar la predicción
        results = model.predict(frame)
        a = results[0].boxes.data
        px = pd.DataFrame(a).astype("float")

        # Listas para contar los objetos en cada área
        counts = [0] * len(areas)

        for index, row in px.iterrows():
            x1, y1, x2, y2, _, d = map(int, row)
            c = class_list[d]
            if 'car' in c:
                cx, cy = (x1 + x2) // 2, (y1 + y2) // 2
                for i, area in enumerate(areas):
                    if cv2.pointPolygonTest(np.array(area, np.int32), (cx, cy), False) >= 0:
                        cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
                        cv2.circle(frame, (cx, cy), 3, (0, 0, 255), -1)
                        counts[i] += 1
                        cv2.putText(frame, c, (x1, y1), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

        # Dibujar las áreas y contar los espacios disponibles
        place_of_free_spaces = []
        free_spaces = 0
        for i, (area, count) in enumerate(zip(areas, counts)):
            color = (0, 0, 255) if count == 1 else (0, 255, 0)
            cv2.polylines(frame, [np.array(area, np.int32)], True, color, 2)
            cv2.putText(frame, str(i + 1), (area[0][0], area[0][1] - 5), cv2.FONT_HERSHEY_COMPLEX, 0.5, color, 1)
            if count == 0:
                place_of_free_spaces.append(i + 1)
                free_spaces += 1

        # Imprimir los sitios libres en la consola
        print("Sitios libres:", place_of_free_spaces)
        print("Total de sitios libres: ", free_spaces)

        # Mostrar los sitios libres en la imagen
        cv2.putText(frame, "Sitios libres: " + ", ".join(map(str, place_of_free_spaces)), (10, 30), cv2.FONT_HERSHEY_PLAIN, 1, (255, 255, 255), 2)

        # Guardar la imagen resultante
        output_filename = 'processed_' + filename
        cv2.imwrite(output_filename, frame)

        
        update_database(free_spaces,codParking)
        
        
        return output_filename
    else:
        print('No se pudo obtener la imagen. Código de estado:', response.status_code)
        return None

# Lista para almacenar las últimas 5 imágenes
recent_images = []

try:
    while True:
        image_path = capture_and_process_image()
        if image_path:
            # Añadir la nueva imagen a la lista
            recent_images.append(image_path)
            # Mantener solo las últimas 5 imágenes
            if len(recent_images) > 5:
                # Eliminar la imagen más antigua
                os.remove(recent_images.pop(0))
        # Esperar 10 segundos antes de la siguiente captura
        time.sleep(10)
except KeyboardInterrupt:
    print("Proceso detenido por el usuario.")
