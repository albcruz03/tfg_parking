import numpy as np
import supervision as sv
from roboflow import Roboflow
import tempfile

SOURCE_VIDEO_PATH = "car2.mp4"
TARGET_VIDEO_PATH = "video_out.mp4"

# use https://roboflow.github.io/polygonzone/ to get the points for your line
polygon = np.array([
    # draw 50x50 box in top left corner
    [497, 72],[737, 80],[845, 856],[385, 876],
    [877, 120],[1181, 752],[1517, 680],[1097, 64]
    
])

rf = Roboflow(api_key="1b6MB6LlQEZyc2Jhxuh5")
project = rf.workspace().project("car-park-slot-detection-using-yolov8")
model = project.version(2).model

# create BYTETracker instance
byte_tracker = sv.ByteTrack(track_thresh=0.25, track_buffer=30, match_thresh=0.8, frame_rate=30)

# create VideoInfo instance
video_info = sv.VideoInfo.from_video_path(SOURCE_VIDEO_PATH)

# create frame generator
generator = sv.get_video_frames_generator(SOURCE_VIDEO_PATH)

# create PolygonZone instance
zone = sv.PolygonZone(polygon=polygon, frame_resolution_wh=(video_info.width, video_info.height))

# create box annotator
box_annotator = sv.BoxAnnotator(thickness=4, text_thickness=4, text_scale=2)

colors = sv.ColorPalette.default()

# create instance of BoxAnnotator
zone_annotator = sv.PolygonZoneAnnotator(thickness=4, text_thickness=4, text_scale=2, zone=zone, color=colors.colors[0])

# define call back function to be used in video processing
def callback(frame: np.ndarray, index:int) -> np.ndarray:
    # model prediction on single frame and conversion to supervision Detections
    # with tempfile.NamedTemporaryFile(suff?ix=".jpg") as temp:
    results = model.predict(frame).json()

    detections = sv.Detections.from_roboflow(results)

    # show detections in real time
    print(detections)

    # tracking detections
    detections = byte_tracker.update_with_detections(detections)

    labels = [
        f"#{tracker_id} {model.dataset_id[class_id]} {confidence:0.2f}"
        for _, _, confidence, class_id, tracker_id
        in detections
    ]

    annotated_frame = box_annotator.annotate(scene=frame, detections=detections, labels=labels)

    annotated_frame = zone_annotator.annotate(scene=annotated_frame)
    # return frame with box and line annotated result
    return annotated_frame

# process the whole video
sv.process_video(
    source_path = SOURCE_VIDEO_PATH,
    target_path = TARGET_VIDEO_PATH,
    callback=callback
)