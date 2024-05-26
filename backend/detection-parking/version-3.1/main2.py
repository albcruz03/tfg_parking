import cv2
import pandas as pd
import numpy as np
from ultralytics import YOLO


# Cargar el modelo YOLO
model = YOLO('yolov8s.pt')

# Definir las áreas de interés
areas = [
    [(281, 40), (386, 44), (389, 80), (289, 85)],
    [(286, 88), (388, 90), (390, 109), (290, 111)],
    [(290, 113), (394, 112), (393, 134), (285, 142)],
    [(275, 137), (389, 140), (401, 177), (277, 196)],
    [(285, 199), (397, 192), (404, 231), (285, 236)],
    [(267, 250), (416, 245), (424, 313), (267, 318)],
    [(270, 327), (426, 320), (430, 380), (272, 400)],
    [(492, 48), (576, 39), (600, 66), (510, 82)],
    [(520, 101), (634, 80), (663, 116), (542, 147)],
    [(566, 139), (658, 119), (681, 161), (584, 180)],
    [(588, 186), (674, 168), (709, 198), (609, 226)],
    [(614, 226), (712, 196), (747, 244), (631, 273)],
    [(632, 280), (767, 246), (794, 304), (653, 337)]
]

# Leer el archivo de clases COCO
with open("coco.txt", "r") as my_file:
    class_list = my_file.read().split("\n")

# Leer la imagen
image_path = 'car.png'  # Cambia a la ruta de tu imagen
frame = cv2.imread(image_path)
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
        free_spaces = free_spaces+1

# Imprimir los sitios libres en la consola
print("Sitios libres:", place_of_free_spaces)
print("Total de sitios libres: ", free_spaces)

# Mostrar los sitios libres en la imagen
cv2.putText(frame, "Sitios libres: " + ", ".join(map(str, place_of_free_spaces)), (10, 30), cv2.FONT_HERSHEY_PLAIN, 1, (255, 255, 255), 2)

# Mostrar la imagen resultante
cv2.imshow("RGB", frame)
cv2.imwrite('car_output.jpg', frame)
cv2.waitKey(0)
cv2.destroyAllWindows()
