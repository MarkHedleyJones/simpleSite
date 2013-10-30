import Image
import os, sys

# adjust width and height to your needs
thumb_a = 256
thumb_b = 196
main_a = 1024
main_b = 768


for root, dirs, files in os.walk('../public_html'):
    for name in files:
        filename = os.path.join(root, name)
        if filename.lower()[-4:] == '.jpg':
            if filename.find('_main') == -1 and filename.find('_thumb') == -1:
                if (os.path.exists(filename[:-4] + '_l_main.jpg') is False and
                    os.path.exists(filename[:-4] + '_p_main.jpg') is False and
                    filename[-11:] != 'thumb_p.jpg' and filename[-11:] != 'thumb_l.jpg'):

                    landscape = True
                    image = Image.open(filename)

                    if image.size[0] < image.size[1]:
                        landscape = False

                    if landscape:
                        dims_main = (main_a, main_b)
                        dims_thumb = (thumb_a, thumb_b)
                    else:
                        dims_main = (main_b, main_a)
                        dims_thumb = (thumb_b, thumb_a)

                    main = image.resize(dims_main, Image.ANTIALIAS)
                    thumb = image.resize(dims_thumb, Image.ANTIALIAS)

                    outputName = filename[:-4]
                    # Append the orientation of the image to its filename
                    if landscape:
                        outputName += '_l'
                    else:
                        outputName += '_p'

                    main.save(outputName + '_main.jpg')
                    thumb.save(outputName + '_thumb.jpg')
                    print 'wrote ' + outputName + '_main.jpg'
                    print 'wrote ' + outputName + '_thumb.jpg'
