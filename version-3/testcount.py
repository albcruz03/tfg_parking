import cv2
import pandas as pd
import numpy as np
from ultralytics import YOLO
import time

model=YOLO('yolov8s.pt')


def RGB(event, x, y, flags, param):
    if event == cv2.EVENT_MOUSEMOVE :  
        colorsBGR = [x, y]
        print(colorsBGR)
        

cv2.namedWindow('RGB')
cv2.setMouseCallback('RGB', RGB)

cap=cv2.VideoCapture('car2.mp4')

my_file = open("coco.txt", "r")
data = my_file.read()
class_list = data.split("\n")
   

area1=[(281, 40),(386, 44),(389, 80),(289, 85)]

area2=[(286, 88),(388, 90),(390, 109),(290, 111)]

area3=[(290,113),(394,112),(393,134),(285,142)]

area4=[(275,137),(389,140),(401,177),(277,196)]

area5=[(285,199),(397,192),(404,231),(285,236)]

area6=[(267,250),(416,245),(424,313),(267,318)]

area7=[(270,327),(426,320),(430,380),(272,400)]

area8=[(492,48),(576,39),(600,66),(510,82)]

area9=[(520,101),(634,80),(663,116),(542,147)]

area10=[(566,139),(658,119),(681,161),(584,180)]

area11=[(588,186),(674,168),(709,198),(609,226)]

area12=[(614,226),(712,196),(747,244),(631,273)]

area13=[(632,280),(767,246),(794,304),(653,337)]


while True:    
    ret,frame = cap.read()
    if not ret:
        break
    time.sleep(1)
    frame=cv2.resize(frame,(1020,500))

    results=model.predict(frame)
 #   print(results)
    a=results[0].boxes.data
    px=pd.DataFrame(a).astype("float")
#    print(px)
    list1=[]
    list2=[]
    list3=[]
    list4=[]
    list5=[]
    list6=[]
    list7=[]
    list8=[]
    list9=[]
    list10=[]
    list11=[]
    list12=[]
    list13=[]
    
    for index,row in px.iterrows():
#        print(row)
 
        x1=int(row[0])
        y1=int(row[1])
        x2=int(row[2])
        y2=int(row[3])
        d=int(row[5])
        c=class_list[d]
        if 'car' in c:
            cx=int(x1+x2)//2
            cy=int(y1+y2)//2

            results1=cv2.pointPolygonTest(np.array(area1,np.int32),((cx,cy)),False)
            if results1>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list1.append(c)
               cv2.putText(frame,str(c),(x1,y1),cv2.FONT_HERSHEY_COMPLEX,0.5,(255,255,255),1)
            
            results2=cv2.pointPolygonTest(np.array(area2,np.int32),((cx,cy)),False)
            if results2>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list2.append(c)
            
            results3=cv2.pointPolygonTest(np.array(area3,np.int32),((cx,cy)),False)
            if results3>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list3.append(c)   
            results4=cv2.pointPolygonTest(np.array(area4,np.int32),((cx,cy)),False)
            if results4>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list4.append(c)  
            results5=cv2.pointPolygonTest(np.array(area5,np.int32),((cx,cy)),False)
            if results5>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list5.append(c)  
            results6=cv2.pointPolygonTest(np.array(area6,np.int32),((cx,cy)),False)
            if results6>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list6.append(c)  
            results7=cv2.pointPolygonTest(np.array(area7,np.int32),((cx,cy)),False)
            if results7>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list7.append(c)   
            results8=cv2.pointPolygonTest(np.array(area8,np.int32),((cx,cy)),False)
            if results8>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list8.append(c)  
            results9=cv2.pointPolygonTest(np.array(area9,np.int32),((cx,cy)),False)
            if results9>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list9.append(c)  
            results10=cv2.pointPolygonTest(np.array(area10,np.int32),((cx,cy)),False)
            if results10>=0:
                cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
                cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
                list10.append(c)     
            results11=cv2.pointPolygonTest(np.array(area11,np.int32),((cx,cy)),False)
            if results11>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list11.append(c)    
            results12=cv2.pointPolygonTest(np.array(area12,np.int32),((cx,cy)),False)
            if results12>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list12.append(c)
            results13=cv2.pointPolygonTest(np.array(area13,np.int32),((cx,cy)),False)
            if results13>=0:
               cv2.rectangle(frame,(x1,y1),(x2,y2),(0,255,0),2)
               cv2.circle(frame,(cx,cy),3,(0,0,255),-1)
               list13.append(c)
            
    a1=(len(list1))
    a2=(len(list2))       
    a3=(len(list3))    
    a4=(len(list4))
    a5=(len(list5))
    a6=(len(list6)) 
    a7=(len(list7))
    a8=(len(list8)) 
    a9=(len(list9))
    a10=(len(list10))
    a11=(len(list11))
    a12=(len(list12))
    a13=(len(list13))
    o=(a1+a2+a3+a4+a5+a6+a7+a8+a9+a10+a11+a12+a13)
    space=(13-o)
    print(space)
    # Codigo para área 1
    if a1 == 1:
        cv2.polylines(frame, [np.array(area1, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('1'), (387, 40), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area1, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('1'), (387, 40), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 2
    if a2 == 1:
        cv2.polylines(frame, [np.array(area2, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('2'), (389, 86), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area2, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('2'), (389, 86), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 3
    if a3 == 1:
        cv2.polylines(frame, [np.array(area3, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('3'), (394, 112), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area3, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('3'), (394, 112), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 4
    if a4 == 1:
        cv2.polylines(frame, [np.array(area4, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('4'), (389, 140), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area4, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('4'), (389, 140), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 5
    if a5 == 1:
        cv2.polylines(frame, [np.array(area5, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('5'), (397, 192), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area5, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('5'), (397, 192), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 6
    if a6 == 1:
        cv2.polylines(frame, [np.array(area6, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('6'), (416, 245), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area6, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('6'), (416, 245), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 7
    if a7 == 1:
        cv2.polylines(frame, [np.array(area7, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('7'), (426, 320), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area7, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('7'), (426, 320), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 8
    if a8 == 1:
        cv2.polylines(frame, [np.array(area8, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('8'), (492, 48), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area8, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('8'), (492, 48), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 9
    if a9 == 1:
        cv2.polylines(frame, [np.array(area9, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('9'), (512, 83), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area9, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('9'), (512, 83), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 10
    if a10 == 1:
        cv2.polylines(frame, [np.array(area10, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('10'), (566,139), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area10, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('10'), (566,139), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 11
    if a11 == 1:
        cv2.polylines(frame, [np.array(area11, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('11'), (588, 186), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area11, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('11'), (588, 186), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    # Código para área 12
    if a12 == 1:
        cv2.polylines(frame, [np.array(area12, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('12'), (614, 226), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area12, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('12'), (614, 226), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
        
    # Código para área 13
    if a13 == 1:
        cv2.polylines(frame, [np.array(area13, np.int32)], True, (0, 0, 255), 2)
        cv2.putText(frame, str('13'), (632,280), cv2.FONT_HERSHEY_COMPLEX, 0.5, (0, 0, 255), 1)
    else:
        cv2.polylines(frame, [np.array(area13, np.int32)], True, (0, 255, 0), 2)
        cv2.putText(frame, str('13'), (632,280), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)

    
    
    cv2.putText(frame,str(space),(23,30),cv2.FONT_HERSHEY_PLAIN,3,(255,255,255),2)

    cv2.imshow("RGB", frame)

    if cv2.waitKey(30)&0xFF==27:
        break
cap.release()
cv2.destroyAllWindows()
#stream.stop()

