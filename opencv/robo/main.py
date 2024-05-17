from roboflow import Roboflow
import supervision as sv
import cv2
import numpy as np

# Inicializar Roboflow
rf = Roboflow(api_key="1b6MB6LlQEZyc2Jhxuh5")

# Obtener el proyecto y modelo de Roboflow
project = rf.workspace().project("final_data_project")
model = project.version(2).model

# Predecir en la imagen
result = model.predict("carPark2.jpg", confidence=40, overlap=30).json()

# Obtener etiquetas de las predicciones
labels = [item["class"] for item in result["predictions"]]

# Crear objetos de detección desde los resultados de Roboflow
detections = sv.Detections.from_inference(result)

# Inicializar el anotador de etiquetas y de caja delimitadora
label_annotator = sv.LabelAnnotator()
bounding_box_annotator = sv.BoundingBoxAnnotator()

# Leer la imagen
image = cv2.imread("carPark2.jpg")

# Anotar la imagen con cajas delimitadoras y etiquetas
annotated_image = bounding_box_annotator.annotate(scene=image, detections=detections)
annotated_image = label_annotator.annotate(scene=annotated_image, detections=detections, labels=labels)

# Segmentación de áreas de estacionamiento (ejemplo con áreas aleatorias)
parking_areas = [
    [(250, 144), (386, 138), (390, 192), (255, 188)],
    [(300, 100), (400, 100), (400, 200), (300, 200)],
    [(100, 300), (200, 300), (200, 400), (100, 400)],
    [(300, 300), (400, 300), (400, 400), (300, 400)]
]

# Dibujar áreas de estacionamiento en la imagen
for area in parking_areas:
    cv2.polylines(annotated_image, [np.array(area)], True, (0, 255, 0), 2)

# Mostrar la imagen
cv2.imshow("Annotated Image", annotated_image)
cv2.waitKey(0)
cv2.destroyAllWindows()
