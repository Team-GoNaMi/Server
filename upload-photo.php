<body>
    <%
		//이미지를 저장할 경로 입력.
		String folderTypePath = "./photo/testdir";
		String name = new String();
		String fileName = new String();
		int sizeLimit = 5 * 1024 * 1024; // 5메가까지 제한 넘어서면 예외발생
		
		try {
			MultipartRequest multi = new MultipartRequest(request, folderTypePath, sizeLimit, new DefaultFileRenamePolicy());
			Enumeration files = multi.getFileNames();
																		 
			//파일 정보가 있다면
			if (files.hasMoreElements()) {
				name = (String) files.nextElement();
				fileName = multi.getFilesystemName(name);
			}

			System.out.println("이미지를 저장하였습니다. : " + fileName);

		} catch (IOException e) {
			out.println("안드로이드 부터 이미지를 받아옵니다.");
		}
    %> 
</body>
