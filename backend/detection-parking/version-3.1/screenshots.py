import requests
from datetime import datetime

# URL de la imagen de la cámara IP
url = 'http://192.168.1.134:4747/cam/1/frame.jpg'

# Realiza la solicitud GET a la URL
response = requests.get(url)

if response.status_code == 200:
    # Nombre del archivo basado en la fecha y hora actual
    filename = datetime.now().strftime('%Y%m%d_%H%M%S') + '.jpg'

    # Guarda la imagen en el directorio actual
    with open(filename, 'wb') as f:
        f.write(response.content)

    print(f'Imagen guardada como {filename}')
else:
    print('No se pudo obtener la imagen. Código de estado:', response.status_code)
