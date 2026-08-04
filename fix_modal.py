import sys

def main():
    file_path = 'resources/views/partials/modals/modal-nuevo-cliente.blade.php'
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    start_idx = content.find('x-data="{')
    if start_idx == -1:
        print("Could not find x-data")
        return

    brace_count = 0
    in_string = False
    escape = False
    end_idx = -1
    
    start_search_idx = start_idx + 8

    for i in range(start_search_idx, len(content)):
        c = content[i]
        
        if escape:
            escape = False
            continue
        
        if c == '\\\\':
            escape = True
            continue
            
        if c == "'" and not in_string:
            in_string = True
        elif c == "'" and in_string:
            in_string = False
            
        if not in_string:
            if c == '{':
                brace_count += 1
            elif c == '}':
                brace_count -= 1
                if brace_count == 0:
                    if content[i+1] == '"':
                        end_idx = i + 2
                        break

    if end_idx == -1:
        print("Failed to find end of x-data")
        return

    x_data_content = content[start_search_idx+1:end_idx-2]
    
    new_content = content[:start_idx] + 'x-data="nuevoClienteComponent()"' + content[end_idx:]
    
    script = f"""
<script>
function nuevoClienteComponent() {{
    return {{
{x_data_content}
    }};
}}
</script>
"""
    new_content += script
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    
    print("Successfully replaced x-data and added script tag.")

if __name__ == '__main__':
    main()
