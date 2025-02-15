import os
import re
import json
import base64
from PyQt5.QtWidgets import (
    QApplication, QMainWindow, QWidget, QVBoxLayout, QHBoxLayout, QListWidget,
    QListWidgetItem, QPushButton, QLineEdit, QLabel, QFileDialog, QInputDialog,
    QComboBox, QMessageBox
)
from PyQt5.QtCore import Qt, QMimeData, QByteArray
from PyQt5.QtGui import QIcon, QDrag

DEFAULT_PATH = r"C:\GithubBlog\GithubBlog\public\book.html"

class BookmarkManager(QMainWindow):
    def __init__(self):
        super().__init__()
        self.bookmarks = {"categories": []}
        self.current_file = DEFAULT_PATH
        self.init_ui()
        self.load_data()

    def init_ui(self):
        self.setWindowTitle("书签管理工具")
        self.setGeometry(300, 300, 800, 600)
        self.setStyleSheet("""
            QMainWindow { background: #f5f5f5; }
            QListWidget { background: white; border: 1px solid #ddd; }
            QPushButton { 
                background: #007BFF; color: white; border: none;
                padding: 5px 10px; border-radius: 3px;
            }
            QPushButton:hover { background: #0056b3; }
            QLineEdit, QComboBox { padding: 5px; border: 1px solid #ddd; }
        """)

        # 主布局
        main_widget = QWidget()
        self.setCentralWidget(main_widget)
        layout = QHBoxLayout(main_widget)

        # 左侧分类列表
        self.category_list = QListWidget()
        self.category_list.setFixedWidth(200)
        self.category_list.itemClicked.connect(self.on_category_selected)
        layout.addWidget(self.category_list)

        # 右侧功能区域
        right_layout = QVBoxLayout()

        # 书签列表
        self.bookmark_list = QListWidget()
        self.bookmark_list.setDragDropMode(QListWidget.InternalMove)
        right_layout.addWidget(self.bookmark_list)

        # 按钮区域
        btn_layout = QHBoxLayout()
        self.btn_up = QPushButton("上移")
        self.btn_down = QPushButton("下移")
        self.btn_add = QPushButton("添加书签")
        self.btn_edit = QPushButton("编辑")
        self.btn_delete = QPushButton("删除")
        self.btn_up.clicked.connect(self.move_up)
        self.btn_down.clicked.connect(self.move_down)
        self.btn_add.clicked.connect(self.add_bookmark)
        self.btn_edit.clicked.connect(self.edit_bookmark)
        self.btn_delete.clicked.connect(self.delete_bookmark)
        btn_layout.addWidget(self.btn_up)
        btn_layout.addWidget(self.btn_down)
        btn_layout.addWidget(self.btn_add)
        btn_layout.addWidget(self.btn_edit)
        btn_layout.addWidget(self.btn_delete)
        right_layout.addLayout(btn_layout)

        # 分类管理
        category_btn_layout = QHBoxLayout()
        self.btn_add_cat = QPushButton("添加分类")
        self.btn_edit_cat = QPushButton("编辑分类")
        self.btn_del_cat = QPushButton("删除分类")
        self.btn_add_cat.clicked.connect(self.add_category)
        self.btn_edit_cat.clicked.connect(self.edit_category)
        self.btn_del_cat.clicked.connect(self.delete_category)
        category_btn_layout.addWidget(self.btn_add_cat)
        category_btn_layout.addWidget(self.btn_edit_cat)
        category_btn_layout.addWidget(self.btn_del_cat)
        right_layout.addLayout(category_btn_layout)

        # 文件操作
        file_btn_layout = QHBoxLayout()
        self.btn_open = QPushButton("打开文件")
        self.btn_save = QPushButton("保存")
        self.btn_open.clicked.connect(self.open_file)
        self.btn_save.clicked.connect(self.save_data)
        file_btn_layout.addWidget(self.btn_open)
        file_btn_layout.addWidget(self.btn_save)
        right_layout.addLayout(file_btn_layout)

        layout.addLayout(right_layout)

    def load_data(self):
        """从HTML加载数据"""
        try:
            with open(self.current_file, 'r', encoding='utf-8') as f:
                content = f.read()
                match = re.search(r'const bookmarksData = ({.*?});', content, re.DOTALL)
                if match:
                    data_str = match.group(1)
                    self.bookmarks = json.loads(data_str)
                    self.update_category_list()
        except Exception as e:
            QMessageBox.warning(self, "错误", f"加载文件失败: {str(e)}")

    def save_data(self):
        """保存数据到HTML"""
        try:
            with open(self.current_file, 'r+', encoding='utf-8') as f:
                content = f.read()
                new_data = json.dumps(self.bookmarks, indent=2, ensure_ascii=False)
                new_content = re.sub(
                    r'(const bookmarksData = ){.*?}(;)',
                    r'\1' + new_data + r'\2',
                    content,
                    flags=re.DOTALL
                )
                f.seek(0)
                f.write(new_content)
                f.truncate()
            QMessageBox.information(self, "成功", "保存成功！")
        except Exception as e:
            QMessageBox.critical(self, "错误", f"保存失败: {str(e)}")

    def update_category_list(self):
        """更新分类列表"""
        self.category_list.clear()
        for cat in self.bookmarks['categories']:
            self.category_list.addItem(cat['name'])

    def on_category_selected(self, item):
        """分类选择事件"""
        self.current_category = next(
            (c for c in self.bookmarks['categories'] if c['name'] == item.text()),
            None
        )
        self.update_bookmark_list()

    def update_bookmark_list(self):
        """更新书签列表"""
        self.bookmark_list.clear()
        if self.current_category:
            for entry in self.current_category['entries']:
                item = QListWidgetItem(f"{entry['title']} ({entry['url']})")
                item.setData(Qt.UserRole, entry)
                self.bookmark_list.addItem(item)

    def move_up(self):
        """上移书签"""
        row = self.bookmark_list.currentRow()
        if row > 0:
            entries = self.current_category['entries']
            entries[row], entries[row-1] = entries[row-1], entries[row]
            self.current_category['entries'] = entries
            self.update_bookmark_list()
            self.bookmark_list.setCurrentRow(row-1)

    def move_down(self):
        """下移书签"""
        row = self.bookmark_list.currentRow()
        if row < len(self.current_category['entries']) - 1:
            entries = self.current_category['entries']
            entries[row], entries[row+1] = entries[row+1], entries[row]
            self.current_category['entries'] = entries
            self.update_bookmark_list()
            self.bookmark_list.setCurrentRow(row+1)

    def add_bookmark(self):
        """添加书签"""
        if not self.current_category:
            QMessageBox.warning(self, "警告", "请先选择分类！")
            return

        title, ok = QInputDialog.getText(self, "添加书签", "请输入网站标题:")
        if not ok: return
        url, ok = QInputDialog.getText(self, "添加书签", "请输入网站URL:")
        if not ok: return

        # 生成iconUrl
        encoded = base64.b64encode(url.encode()).decode()
        icon_url = f"https://favicon.png.pub/v1/{encoded}"

        self.current_category['entries'].append({
            "title": title,
            "url": url,
            "iconUrl": icon_url
        })
        self.update_bookmark_list()

    def edit_bookmark(self):
        """编辑书签"""
        item = self.bookmark_list.currentItem()
        if not item: return

        entry = item.data(Qt.UserRole)
        title, ok = QInputDialog.getText(self, "编辑书签", "新标题:", text=entry['title'])
        if not ok: return
        url, ok = QInputDialog.getText(self, "编辑书签", "新URL:", text=entry['url'])
        if not ok: return

        # 更新数据
        entry['title'] = title
        entry['url'] = url
        entry['iconUrl'] = f"https://favicon.png.pub/v1/{base64.b64encode(url.encode()).decode()}"
        self.update_bookmark_list()

    def delete_bookmark(self):
        """删除书签"""
        row = self.bookmark_list.currentRow()
        if row >= 0:
            del self.current_category['entries'][row]
            self.update_bookmark_list()

    def add_category(self):
        """添加分类"""
        categories = [c['name'] for c in self.bookmarks['categories']]
        name, ok = QInputDialog.getItem(
            self, "添加分类", "选择或输入分类名称:",
            categories, editable=True
        )
        if ok and name:
            if name not in categories:
                self.bookmarks['categories'].append({
                    "name": name,
                    "entries": []
                })
                self.update_category_list()

    def edit_category(self):
        """编辑分类名称"""
        old_name = self.category_list.currentItem().text()
        new_name, ok = QInputDialog.getText(self, "编辑分类", "新名称:", text=old_name)
        if ok and new_name:
            for cat in self.bookmarks['categories']:
                if cat['name'] == old_name:
                    cat['name'] = new_name
                    break
            self.update_category_list()

    def delete_category(self):
        """删除分类"""
        item = self.category_list.currentItem()
        if item:
            self.bookmarks['categories'] = [
                c for c in self.bookmarks['categories']
                if c['name'] != item.text()
            ]
            self.update_category_list()

    def open_file(self):
        """打开文件对话框"""
        path, _ = QFileDialog.getOpenFileName(
            self, "打开书签文件", 
            os.path.dirname(DEFAULT_PATH), 
            "HTML Files (*.html)"
        )
        if path:
            self.current_file = path
            self.load_data()

if __name__ == "__main__":
    app = QApplication([])
    window = BookmarkManager()
    window.show()
    app.exec_()