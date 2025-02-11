import json
import base64
import os
from PyQt5.QtWidgets import (
    QApplication, QMainWindow, QListWidget, QListWidgetItem, QPushButton,
    QVBoxLayout, QHBoxLayout, QWidget, QLineEdit, QLabel, QInputDialog,
    QMessageBox, QFileDialog
)
from PyQt5.QtCore import Qt
from PyQt5.QtGui import QIcon
from urllib.parse import urlparse

class BookmarkManager(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("书签管理工具")
        self.setGeometry(100, 100, 800, 600)
        self.bookmarks_path = "C:\\GithubBlog\\GithubBlog\\public\\book.html"
        self.bookmarks = self.load_bookmarks()
        self.init_ui()

    def load_bookmarks(self):
        try:
            with open(self.bookmarks_path, "r", encoding="utf-8") as file:
                content = file.read()
            start = content.find("const bookmarksData = ") + len("const bookmarksData = ")
            end = content.find(";", start)
            bookmarks_data = content[start:end].strip()
            # 确保JSON格式正确
            bookmarks_data = bookmarks_data.replace("'", '"')
            return json.loads(bookmarks_data)
        except json.JSONDecodeError as e:
            QMessageBox.critical(self, "错误", f"无法加载书签数据：JSON格式错误\n{e}")
        except Exception as e:
            QMessageBox.critical(self, "错误", f"无法加载书签数据：{e}")
        return {"categories": []}

    def save_bookmarks(self):
        try:
            with open(self.bookmarks_path, "r", encoding="utf-8") as file:
                content = file.read()
            bookmarks_data = json.dumps(self.bookmarks, ensure_ascii=False, indent=4)
            new_content = content.replace(
                "const bookmarksData = {};".format(json.dumps(self.load_bookmarks(), ensure_ascii=False, indent=4)),
                "const bookmarksData = {};".format(bookmarks_data)
            )
            with open(self.bookmarks_path, "w", encoding="utf-8") as file:
                file.write(new_content)
            QMessageBox.information(self, "成功", "书签数据已保存")
        except Exception as e:
            QMessageBox.critical(self, "错误", f"无法保存书签数据：{e}")

    def init_ui(self):
        self.central_widget = QWidget()
        self.setCentralWidget(self.central_widget)
        self.layout = QHBoxLayout()
        self.central_widget.setLayout(self.layout)

        # 左侧：分类列表
        self.category_list = QListWidget()
        self.category_list.itemSelectionChanged.connect(self.on_category_select)
        self.layout.addWidget(self.category_list)

        # 右侧：网站列表和操作按钮
        self.website_layout = QVBoxLayout()
        self.layout.addLayout(self.website_layout)

        self.website_list = QListWidget()
        self.website_layout.addWidget(self.website_list)

        self.button_layout = QHBoxLayout()
        self.website_layout.addLayout(self.button_layout)

        self.add_website_button = QPushButton("添加网站")
        self.add_website_button.clicked.connect(self.add_website)
        self.button_layout.addWidget(self.add_website_button)

        self.delete_website_button = QPushButton("删除网站")
        self.delete_website_button.clicked.connect(self.delete_website)
        self.button_layout.addWidget(self.delete_website_button)

        self.add_category_button = QPushButton("添加分类")
        self.add_category_button.clicked.connect(self.add_category)
        self.button_layout.addWidget(self.add_category_button)

        self.delete_category_button = QPushButton("删除分类")
        self.delete_category_button.clicked.connect(self.delete_category)
        self.button_layout.addWidget(self.delete_category_button)

        self.save_button = QPushButton("保存")
        self.save_button.clicked.connect(self.save_bookmarks)
        self.button_layout.addWidget(self.save_button)

        self.update_category_list()

    def update_category_list(self):
        self.category_list.clear()
        for category in self.bookmarks["categories"]:
            item = QListWidgetItem(category["name"])
            item.setData(Qt.UserRole, category)
            self.category_list.addItem(item)

    def update_website_list(self, category):
        self.website_list.clear()
        for entry in category["entries"]:
            item = QListWidgetItem(entry["title"])
            item.setData(Qt.UserRole, entry)
            self.website_list.addItem(item)

    def on_category_select(self):
        selected_item = self.category_list.currentItem()
        if selected_item:
            category = selected_item.data(Qt.UserRole)
            self.update_website_list(category)

    def add_website(self):
        selected_item = self.category_list.currentItem()
        if not selected_item:
            QMessageBox.critical(self, "错误", "请先选择一个分类")
            return

        category = selected_item.data(Qt.UserRole)
        title, ok = QInputDialog.getText(self, "输入", "网站标题:")
        if not ok or not title:
            return

        url, ok = QInputDialog.getText(self, "输入", "网站URL:")
        if not ok or not url:
            return

        icon_url = self.generate_icon_url(url)
        category["entries"].append({"title": title, "url": url, "iconUrl": icon_url})
        self.update_website_list(category)

    def delete_website(self):
        selected_item = self.website_list.currentItem()
        if not selected_item:
            return

        selected_category_item = self.category_list.currentItem()
        if not selected_category_item:
            return

        category = selected_category_item.data(Qt.UserRole)
        entry = selected_item.data(Qt.UserRole)
        category["entries"].remove(entry)
        self.update_website_list(category)

    def add_category(self):
        category_name, ok = QInputDialog.getText(self, "输入", "分类名称:")
        if not ok or not category_name:
            return

        existing_category = next((c for c in self.bookmarks["categories"] if c["name"] == category_name), None)
        if existing_category:
            QMessageBox.critical(self, "错误", "分类已存在")
            return

        self.bookmarks["categories"].append({"name": category_name, "entries": []})
        self.update_category_list()

    def delete_category(self):
        selected_item = self.category_list.currentItem()
        if not selected_item:
            return

        category = selected_item.data(Qt.UserRole)
        self.bookmarks["categories"].remove(category)
        self.update_category_list()

    def generate_icon_url(self, url):
        parsed_url = urlparse(url)
        base64_url = base64.urlsafe_b64encode(parsed_url.scheme.encode() + b"://" + parsed_url.netloc.encode()).decode()
        return f"https://favicon.png.pub/v1/{base64_url}"

if __name__ == "__main__":
    app = QApplication([])
    window = BookmarkManager()
    window.show()
    app.exec_()