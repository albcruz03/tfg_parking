from inference_sdk import InferenceHTTPClient, InferenceConfiguration

def organizar_resultado(result):
    organized_result = {
        'time': result['time'],
        'image': result['image'],
        'occupied_sites': []
    }

    num_sites_occupied = 0

    for pred in result['predictions']:
        if pred['class'] == 'occupied':
            organized_result['occupied_sites'].append({
                'x': pred['x'],
                'y': pred['y'],
                'width': pred['width'],
                'height': pred['height'],
                'confidence': pred['confidence'],
                'detection_id': pred['detection_id']
            })
            num_sites_occupied += 1

    organized_result['num_sites_occupied'] = num_sites_occupied

    return organized_result

image_url = "carParkImg.png"

custom_configuration = InferenceConfiguration(confidence_threshold=0.3, iou_threshold=0.8)
# Replace ROBOFLOW_API_KEY with your Roboflow API Key
CLIENT = InferenceHTTPClient(
    api_url="https://detect.roboflow.com",
    api_key="1b6MB6LlQEZyc2Jhxuh5"
)

# Select API version 0
CLIENT.select_api_v0()
result = CLIENT.infer(image_url, model_id="final_data_project/2")
print("Resultado de la inferencia:")
resultado_organizado = organizar_resultado(result)
print(resultado_organizado)
print("Número de sitios ocupados:", resultado_organizado['num_sites_occupied'])

# Configure with custom configuration
CLIENT.configure(custom_configuration)
result = CLIENT.infer(image_url, model_id="final_data_project/2")
print("Resultado de la inferencia con configuración personalizada:")
resultado_organizado = organizar_resultado(result)
print(resultado_organizado)
print("Número de sitios ocupados:", resultado_organizado['num_sites_occupied'])

# Select model
CLIENT.select_model(model_id="final_data_project/2")
result = CLIENT.infer(image_url)
print("Resultado de la inferencia con modelo seleccionado:")
resultado_organizado = organizar_resultado(result)
print(resultado_organizado)
print("Número de sitios ocupados:", resultado_organizado['num_sites_occupied'])

# Infer with current configuration
result = CLIENT.infer(image_url)
print("Resultado de la inferencia con la configuración actual:")
resultado_organizado = organizar_resultado(result)
print(resultado_organizado)
print("Número de sitios ocupados:", resultado_organizado['num_sites_occupied'])
