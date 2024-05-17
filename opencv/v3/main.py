import cv2
import numpy as np
from ultralytics import YOLO
import time

# Cargar el modelo YOLO
model = YOLO('yolov8s.pt')

# Leer el archivo 'coco.txt' una vez fuera del bucle
with open("coco.txt", "r") as my_file:
    class_list = my_file.read().split("\n")

# Definir áreas
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

# Función para dibujar y etiquetar áreas
def draw_and_label_area(frame, area_index, area_coords, count):
    cv2.polylines(frame, [np.array(area_coords, np.int32)], True, (0, 0, 255), 2)
    cv2.putText(frame, str(area_index), (area_coords[0][0], area_coords[0][1] - 10), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    cv2.putText(frame, str(count), (area_coords[0][0], area_coords[0][1] - 25), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

# Capturar video
cap = cv2.VideoCapture('http://192.168.1.134:4747/video')

# Esperar un momento antes de mostrar la ventana
time.sleep(1)

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # Redimensionar fotograma
    frame = cv2.resize(frame, (1020, 500))

    # Predecir usando YOLO
    results = model(frame)
    print(results)


    # Obtener detecciones
    if isinstance(results[0].boxes, list):
        detections = results.pred[0]
    else:
        detections = results.pred

    # Contar objetos en áreas
    counts = [0] * len(areas)
    if detections is not None:
        for box in detections:
            for i, area in enumerate(areas):
                if cv2.pointPolygonTest(np.array(area, np.int32), (box[0] + box[2]) / 2, False) >= 0:
                    counts[i] += 1
                    cv2.rectangle(frame, (int(box[0]), int(box[1])), (int(box[2]), int(box[3])), (0, 255, 0), 2)
                    cv2.circle(frame, (int((box[0] + box[2]) / 2), int((box[1] + box[3]) / 2)), 3, (0, 0, 255), -1)
                    cv2.putText(frame, class_list[int(box[5])], (int(box[0]), int(box[1])), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Dibujar y etiquetar áreas
    for i, area in enumerate(areas):
        draw_and_label_area(frame, i+1, area, counts[i])

    cv2.imshow("RGB", frame)

    if cv2.waitKey(30) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
