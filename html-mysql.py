from flask import Flask, jsonify, request, render_template, redirect, url_for, send_file, session
from flask_cors import CORS
import mysql.connector
from mysql.connector import Error
import string
import random
from PIL import Image, ImageDraw, ImageFont, ImageFilter
from io import BytesIO
import os

app = Flask(__name__)
CORS(app)

# 設定 Secret Key (供 session 使用)
app.secret_key = os.urandom(24)

# 註冊暫存資料
registerTempData = {
    "name": "",
    "major": "",
}

# 設定資料庫連線參數
connection = mysql.connector.connect(
    host='localhost',
    database='mydatabase',
    user='root',
    password='testmysqlroot@'
)

# 生成驗證碼並存入 session
def generate_captcha():
    captcha = ''.join(random.choices(string.ascii_uppercase + string.digits, k=6))
    session['captcha'] = captcha
    return captcha

# 產生圖片驗證碼
def generate_captcha_image():
    captcha = session.get('captcha', 'XXXXXX')  # 用 session 裡的 captcha

    width, height = 200, 60
    image = Image.new('RGB', (width, height), (255, 255, 255))
    draw = ImageDraw.Draw(image)

    try:
        font = ImageFont.truetype("arial.ttf", 36)
    except:
        font = ImageFont.load_default()

    for _ in range(5):
        x1, y1 = random.randint(0, width), random.randint(0, height)
        x2, y2 = random.randint(0, width), random.randint(0, height)
        draw.line([(x1, y1), (x2, y2)], fill=(100, 100, 100), width=2)

    for i, char in enumerate(captcha):
        x = 25 + i * 25 + random.randint(-3, 3)
        y = 10 + random.randint(-3, 3)
        draw.text((x, y), char, fill=(0, 0, 0), font=font)

    # 濾波器
    image = image.filter(ImageFilter.SMOOTH)

    img_io = BytesIO()
    image.save(img_io, 'PNG')
    img_io.seek(0)
    return img_io


#呈現的首頁
@app.route("/")
def index():
    return redirect(url_for('register'))

@app.route("/register", methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        captcha = generate_captcha()
        return render_template('captcha_verification.html', captcha_image='/captcha_image.png?rand={}'.format(random.random()))
    return render_template('register.html')

@app.route('/captcha_image.png')
def captcha_image():
    # 不需要傳入 captcha，函數內會處理
    img_io = generate_captcha_image()
    return send_file(img_io, mimetype='image/png')

@app.route("/student/validatemajor", methods=['POST'])
def student_register_account_validate():
    response_object = {'status': 'success', 'code': 200}
    user_captcha = request.form.get('captcha', '').strip().upper()
    session_captcha = session.get('captcha', '').strip().upper()

    print("user_captcha:", user_captcha)
    print("session_captcha:", session_captcha)

    if user_captcha == session_captcha:
        try:
            registerTempData["name"] = request.form.get("name", "").strip()
            registerTempData["major"] = request.form.get("major", "").strip()

            sql = "INSERT INTO student(name, major) VALUES (%s, %s);"
            new_data = (registerTempData["name"], registerTempData["major"])
            cursor = connection.cursor()
            cursor.execute(sql, new_data)
            connection.commit()

            response_object['message'] = 'Your account is activated!'
            response_object['data'] = True

            registerTempData["name"] = ""
            registerTempData["major"] = ""
            session.pop('captcha', None)

        except Error as e:
            print("Database error:", e)
            response_object['status'] = 'fail'
            response_object['code'] = 500
            response_object['message'] = 'Database error occurred!'
            response_object['data'] = False
    else:
        response_object['message'] = 'The captcha is not correct!'
        response_object['data'] = False

    return jsonify(response_object)

if __name__ == "__main__":
    app.run(debug=True)
