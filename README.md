# CODER 專案小工具

## 安裝步驟

```bash
git clone git@github.com:piedasing/-coder-cli.git

npm install

# 將專案安裝成全域
npm link
# 或
npm install . -g 
```

## 使用方式

```bash
coder-cli create [projectName] [project]
```

| params      | required | description |
| :---------: | :------: | :---------- |
| projectName | Y        | 專案資料夾名稱<br>如未存在，將會自動建立<br>如已存在，內容須為空才可建立專案，否則將噴錯 |
| project     | N        | 專案類型 (vite, admin)，預設為 vite |
