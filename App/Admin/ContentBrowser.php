<?php
// content_selector.php

function renderContentSelector($directory, $baseUrl, $standalone = false) {
    $files = scandir($directory);
    
    if ($standalone) {
        /*echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>File Selector</title>
        </head>
        <body>';*/
    }
    ?>
    
    <!-- Modal -->
    <div id="contentModal" class="content-modal" style="<?php echo $standalone ? 'display: flex; position: relative; background: none; height: auto;' : ''; ?>">
        <div class="content-modal-inner">
            <div class="content-header">
                <h2>Select Content</h2>
                <div class="header-controls">
                    <span id="multiple" class="multiple-badge">Multiple</span>
                    <?php if (!$standalone): ?>
                        <button onclick="closeContentModal()" class="close-btn">&times;</button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="content-main">
                <div class="content-browser">
                    <?php foreach ($files as $file): 
                        if ($file === '.' || $file === '..') continue;
                        $filePath = $directory . '/' . $file;
                        $fileUrl  = $baseUrl . '/' . rawurlencode($file);
                        if (!is_file($filePath)) continue;

                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $mime = mime_content_type($filePath);
                        $size = filesize($filePath);
                        $modified = filemtime($filePath);

                        $previewable = false;
                        if (strpos($mime, 'image/') === 0) {
                            $previewable = true;
                        }
                    ?>
                        <div class="file-box" 
                             data-url="<?php echo htmlspecialchars($fileUrl); ?>"
                             data-name="<?php echo htmlspecialchars($file); ?>"
                             data-size="<?php echo $size; ?>"
                             data-modified="<?php echo $modified; ?>"
                             data-type="<?php echo htmlspecialchars($ext); ?>"
                             onclick="chooseFile(this)">
                            <div class="thumb">
                                <?php if ($previewable): ?>
                                    <img src="<?php echo htmlspecialchars($fileUrl); ?>" alt="<?php echo htmlspecialchars($file); ?>">
                                <?php else: ?>
                                    <div class="file-icon">
                                        <?php if (in_array($ext, ['zip','rar','7z'])): ?>
                                            📦
                                        <?php elseif ($ext === 'pdf'): ?>
                                            📄
                                        <?php elseif (in_array($ext, ['mp3','wav','ogg'])): ?>
                                            🎵
                                        <?php elseif (in_array($ext, ['mp4','avi','mkv'])): ?>
                                            🎬
                                        <?php elseif (in_array($ext, ['txt','md'])): ?>
                                            📝
                                        <?php elseif (in_array($ext, ['html','css','js','php'])): ?>
                                            💾
                                        <?php else: ?>
                                            📄
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="file-name"><?php echo htmlspecialchars(strlen($file) > 15 ? substr($file, 0, 12) . '...' : $file); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="sidebar" id="sidebar">
                    <div class="sidebar-content">
                        <div class="no-selection">
                            <div class="placeholder-icon">📁</div>
                            <p>Select a file to view details</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content-footer">
                <div class="selection-info">
                    <span id="selectionCount">0 files selected</span>
                </div>
                <button class="select-btn" onclick="finalizeSelection()">Select</button>
            </div>
        </div>
    </div>

    <style>
    .content-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
    }
    .content-modal.standalone {
        position: relative;
        background: none;
        width: auto;
        height: auto;
    }
    .content-modal-inner {
        background: #fff;
        border-radius: 12px;
        max-width: 1200px;
        width: 95%;
        max-height: 90vh;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e0e0e0;
        background: #f8f9fa;
    }
    .content-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }
    .header-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .multiple-badge {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        display: none;
    }
    .content-main {
        display: flex;
        flex: 1;
        min-height: 0;
    }
    .content-browser {
        flex: 1;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 16px;
        overflow-y: auto;
        align-content: start;
    }
    .sidebar {
        width: 280px;
        border-left: 1px solid #e0e0e0;
        background: #fafafa;
        overflow-y: auto;
    }
    .sidebar-content {
        padding: 20px;
    }
    .no-selection {
        text-align: center;
        color: #666;
        margin-top: 60px;
    }
    .placeholder-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .file-details h3 {
        margin: 0 0 16px 0;
        font-size: 16px;
        color: #333;
        word-break: break-all;
    }
    .file-preview {
        margin-bottom: 16px;
        text-align: center;
    }
    .file-preview img {
        max-width: 100%;
        max-height: 120px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .file-preview .preview-icon {
        font-size: 64px;
        opacity: 0.7;
    }
    .file-info {
        space-y: 8px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #666;
        font-weight: 500;
    }
    .info-value {
        color: #333;
        text-align: right;
        word-break: break-all;
        max-width: 60%;
    }
    .file-box {
        border: 2px solid #f0f0f0;
        padding: 12px;
        text-align: center;
        background: #fff;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .file-box:hover {
        border-color: #2196f3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33,150,243,0.15);
    }
    .file-box.selected {
        border-color: #2196f3;
        background: #f3f8ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33,150,243,0.2);
    }
    .thumb {
        height: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 8px;
        border-radius: 6px;
        overflow: hidden;
    }
    .file-box img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 4px;
        object-fit: cover;
    }
    .file-icon {
        font-size: 36px;
        opacity: 0.8;
    }
    .file-name {
        font-size: 12px;
        color: #555;
        font-weight: 500;
        word-break: break-word;
        line-height: 1.3;
    }
    .content-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-top: 1px solid #e0e0e0;
        background: #f8f9fa;
    }
    .selection-info {
        color: #666;
        font-size: 14px;
    }
    .select-btn {
        background: #2196f3;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s ease;
    }
    .select-btn:hover {
        background: #1976d2;
    }
    .select-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.2s ease;
    }
    .close-btn:hover {
        background: #e0e0e0;
    }
    
    @media (max-width: 768px) {
        .content-main {
            flex-direction: column;
        }
        .sidebar {
            width: auto;
            border-left: none;
            border-top: 1px solid #e0e0e0;
            max-height: 200px;
        }
        .content-browser {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }
    }
    </style>

    <script>
    let contentMulti = false;
    let selectedFiles = [];

    function openContentModal(multi = false) {
        contentMulti = multi;
        selectedFiles = [];
        updateMultipleBadge();
        updateSelectionCount();
        clearSidebar();
        document.querySelectorAll('.file-box').forEach(f => f.classList.remove('selected'));
        document.getElementById("contentModal").style.display = "flex";
    }

    function closeContentModal() {
        document.getElementById("contentModal").style.display = "none";
    }

    function chooseFile(el) {
        const url = el.getAttribute("data-url");

        if (contentMulti) {
            el.classList.toggle("selected");
            if (selectedFiles.includes(url)) {
                selectedFiles = selectedFiles.filter(f => f !== url);
            } else {
                selectedFiles.push(url);
            }
        } else {
            document.querySelectorAll('.file-box').forEach(f => f.classList.remove('selected'));
            el.classList.add("selected");
            selectedFiles = [url];
        }
        
        updateSelectionCount();
        showFileDetails(el);
    }

    function showFileDetails(el) {
        const name = el.getAttribute("data-name");
        const size = parseInt(el.getAttribute("data-size"));
        const modified = parseInt(el.getAttribute("data-modified"));
        const type = el.getAttribute("data-type");
        const url = el.getAttribute("data-url");
        
        const isImage = el.querySelector('img');
        const icon = el.querySelector('.file-icon')?.textContent || '📄';
        
        const sizeFormatted = formatFileSize(size);
        const dateFormatted = new Date(modified * 1000).toLocaleDateString();
        
        const sidebar = document.getElementById('sidebar');
        sidebar.innerHTML = `
            <div class="sidebar-content">
                <div class="file-details">
                    <h3>${name}</h3>
                    <div class="file-preview">
                        ${isImage ? `<img src="${url}" alt="${name}">` : `<div class="preview-icon">${icon}</div>`}
                    </div>
                    <div class="file-info">
                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value">${type.toUpperCase() || 'Unknown'}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Size</span>
                            <span class="info-value">${sizeFormatted}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Modified</span>
                            <span class="info-value">${dateFormatted}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Selected</span>
                            <span class="info-value">${el.classList.contains('selected') ? 'Yes' : 'No'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function clearSidebar() {
        document.getElementById('sidebar').innerHTML = `
            <div class="sidebar-content">
                <div class="no-selection">
                    <div class="placeholder-icon">📁</div>
                    <p>Select a file to view details</p>
                </div>
            </div>
        `;
    }

    function updateMultipleBadge() {
        document.getElementById("multiple").style.display = contentMulti ? "block" : "none";
    }

    function updateSelectionCount() {
        const count = selectedFiles.length;
        const text = count === 0 ? "No files selected" : 
                    count === 1 ? "1 file selected" : 
                    `${count} files selected`;
        document.getElementById("selectionCount").textContent = text;
        
        const selectBtn = document.querySelector('.select-btn');
        selectBtn.disabled = count === 0;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function finalizeSelection() {
        if (selectedFiles.length === 0) return;
        
        if (typeof window.receiveSelectedFile === "function") {
            if (contentMulti) {
                window.receiveSelectedFile(selectedFiles);
            } else {
                window.receiveSelectedFile(selectedFiles[0] || null);
            }
            closeContentModal();
        } else {
            alert("Selected: " + (contentMulti ? selectedFiles.join("\\n") : selectedFiles[0]));
        }
    }

    // Initialize if standalone
    <?php if ($standalone): ?>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("contentModal").classList.add('standalone');
        document.getElementById("contentModal").style.display = "flex";
        
        // Example callback for standalone mode
        window.receiveSelectedFile = function(fileUrl) {
            console.log('Selected file(s):', fileUrl);
            alert('Selected: ' + (Array.isArray(fileUrl) ? fileUrl.join('\\n') : fileUrl));
        };
    });
    <?php endif; ?>
    </script>

    <?php
}
?>